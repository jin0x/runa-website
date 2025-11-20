<?php

namespace App\Console\Commands;

use WP_CLI;
use Exception;

class ImportCompanies
{
    /**
     * Import companies from CSV file
     *
     * ## OPTIONS
     *
     * [--file=<file>]
     * : Path to the CSV file. Default: companies-import.csv
     *
     * [--limit=<number>]
     * : Limit the number of companies to import. Default: all
     *
     * [--dry-run]
     * : Preview the import without actually creating posts
     *
     * [--verbose]
     * : Show detailed output
     *
     * ## EXAMPLES
     *
     *     # Test import with 5 companies
     *     wp runa import-companies --limit=5
     *
     *     # Dry run to see what would be imported
     *     wp runa import-companies --dry-run --limit=10
     *
     *     # Import all companies from custom file
     *     wp runa import-companies --file=my-companies.csv
     *
     * @when after_wp_load
     */
    public function __invoke($args, $assoc_args)
    {
        $file = $assoc_args['file'] ?? 'companies-import.csv';
        $limit = isset($assoc_args['limit']) ? (int)$assoc_args['limit'] : null;
        $dry_run = isset($assoc_args['dry-run']);
        $verbose = isset($assoc_args['verbose']);

        // Make file path absolute if it's relative
        if (!str_starts_with($file, '/')) {
            $file = ABSPATH . $file;
        }

        // Validate the file path to prevent path traversal attacks
        $validated_file = $this->validateFilePath($file);
        if ($validated_file === false) {
            WP_CLI::error("Invalid file path. File must be within WordPress directory.");
        }
        $file = $validated_file;

        WP_CLI::log("🚀 Companies Import Tool");
        WP_CLI::log("========================");

        if (!file_exists($file)) {
            WP_CLI::error("CSV file not found: {$file}");
        }

        if ($dry_run) {
            WP_CLI::log("🔍 DRY RUN MODE - No data will be imported");
        }

        try {
            $this->importCompanies($file, $limit, $dry_run, $verbose);
        } catch (Exception $e) {
            WP_CLI::error("Import failed: " . $e->getMessage());
        }
    }

    /**
     * Import companies from CSV file
     */
    private function importCompanies($file, $limit, $dry_run, $verbose)
    {
        $csv = fopen($file, 'r');
        if (!$csv) {
            throw new Exception("Could not open CSV file: {$file}");
        }

        // Read header row
        $headers = fgetcsv($csv);
        if (!$headers) {
            throw new Exception("Invalid CSV file - no headers found");
        }

        $this->validateHeaders($headers);

        $processed = 0;
        $created = 0;
        $errors = 0;
        $skipped = 0;

        WP_CLI::log("📄 Processing CSV file: " . basename($file));
        WP_CLI::log("📊 Headers found: " . implode(', ', $headers));

        if ($limit) {
            WP_CLI::log("🎯 Limiting import to {$limit} companies");
        }

        // Start progress tracking
        $progress = null;
        if (!$dry_run && !$verbose) {
            // Count total rows first for progress bar
            $total_rows = $this->countCsvRows($file) - 1; // Subtract header
            if ($limit && $limit < $total_rows) {
                $total_rows = $limit;
            }
            $progress = \WP_CLI\Utils\make_progress_bar('Importing companies', $total_rows);
        }

        while (($row = fgetcsv($csv)) !== false) {
            if ($limit && $processed >= $limit) {
                break;
            }

            $processed++;

            try {
                $company_data = array_combine($headers, $row);

                if ($verbose) {
                    WP_CLI::log("\n--- Processing Company #{$processed} ---");
                    WP_CLI::log("Name: " . $company_data['post_title']);
                    WP_CLI::log("Slug: " . $company_data['company_slug']);
                    WP_CLI::log("Country: " . $company_data['country_name']);
                    WP_CLI::log("Currency: " . $company_data['currency']);
                }

                if ($dry_run) {
                    $this->previewCompany($company_data);
                    $created++;
                } else {
                    $result = $this->createCompany($company_data, $verbose);
                    if ($result === 'created') {
                        $created++;
                    } elseif ($result === 'skipped') {
                        $skipped++;
                    }
                }

                if ($progress) {
                    $progress->tick();
                }

            } catch (Exception $e) {
                $errors++;
                if ($verbose) {
                    WP_CLI::warning("Error processing company #{$processed}: " . $e->getMessage());
                }

                if ($progress) {
                    $progress->tick();
                }
            }
        }

        fclose($csv);

        if ($progress) {
            $progress->finish();
        }

        // Final report
        WP_CLI::log("\n✅ Import completed!");
        WP_CLI::log("📊 Summary:");
        WP_CLI::log("  - Processed: {$processed} companies");
        WP_CLI::log("  - " . ($dry_run ? "Would create" : "Created") . ": {$created} companies");

        if ($skipped > 0) {
            WP_CLI::log("  - Skipped (duplicates): {$skipped} companies");
        }

        if ($errors > 0) {
            WP_CLI::log("  - Errors: {$errors} companies");
        }

        if (!$dry_run && $created > 0) {
            WP_CLI::success("Successfully imported {$created} companies!");
        }
    }

    /**
     * Validate CSV headers
     */
    private function validateHeaders($headers)
    {
        $required_headers = [
            'post_title',
            'company_slug',
            'country_code',
            'country_name',
            'currency',
            'categories',
            'taxonomy_company_country',
            'taxonomy_company_category'
        ];

        $missing = array_diff($required_headers, $headers);
        if (!empty($missing)) {
            throw new Exception("Missing required CSV headers: " . implode(', ', $missing));
        }
    }

    /**
     * Preview company data (dry run)
     */
    private function previewCompany($data)
    {
        WP_CLI::log("  📋 Would create post: " . $data['post_title']);
        WP_CLI::log("    - Slug: " . $data['company_slug']);
        WP_CLI::log("    - Country: " . $data['country_name'] . " ({$data['country_code']})");
        WP_CLI::log("    - Currency: " . $data['currency']);

        if (!empty($data['categories'])) {
            WP_CLI::log("    - Categories: " . $data['categories']);
        }
    }

    /**
     * Create company post with ACF fields and taxonomies
     */
    private function createCompany($data, $verbose = false)
    {
        // Check for duplicate by company_slug
        $existing = get_posts([
            'post_type' => 'company',
            'meta_query' => [
                [
                    'key' => 'company_slug',
                    'value' => $data['company_slug'],
                    'compare' => '='
                ]
            ],
            'post_status' => 'any',
            'numberposts' => 1
        ]);

        if (!empty($existing)) {
            if ($verbose) {
                WP_CLI::log("  ⏭️  Skipping duplicate: " . $data['post_title']);
            }
            return 'skipped';
        }

        // Create the post
        $post_data = [
            'post_title' => $data['post_title'],
            'post_type' => 'company',
            'post_status' => 'publish'
        ];

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            throw new Exception("Failed to create post: " . $post_id->get_error_message());
        }

        // Add ACF fields
        $this->addAcfFields($post_id, $data);

        // Add taxonomies
        $this->addTaxonomies($post_id, $data);

        if ($verbose) {
            WP_CLI::log("  ✅ Created company: " . $data['post_title'] . " (ID: {$post_id})");
        }

        return 'created';
    }

    /**
     * Add ACF fields to company post
     */
    private function addAcfFields($post_id, $data)
    {
        // Use WordPress native functions instead of ACF functions for CLI compatibility
        update_post_meta($post_id, 'company_slug', $data['company_slug']);
        update_post_meta($post_id, 'country_code', $data['country_code']);
        update_post_meta($post_id, 'country_name', $data['country_name']);
        update_post_meta($post_id, 'company_currency', $data['currency']);

        if (!empty($data['image_url'])) {
            update_post_meta($post_id, 'image_url', $data['image_url']);
        }
    }

    /**
     * Add taxonomies to company post
     */
    private function addTaxonomies($post_id, $data)
    {
        // Add country taxonomy
        if (!empty($data['taxonomy_company_country'])) {
            $country_name = trim($data['taxonomy_company_country']);

            // Check if term already exists
            $existing_term = get_term_by('name', $country_name, 'company_country');

            if ($existing_term) {
                // Use existing term
                $country_term_id = $existing_term->term_id;
            } else {
                // Create new term
                $country_term = wp_insert_term($country_name, 'company_country');
                if (is_wp_error($country_term)) {
                    throw new Exception("Failed to create country term '{$country_name}': " . $country_term->get_error_message());
                }
                $country_term_id = $country_term['term_id'];
            }

            // Assign term to post
            $result = wp_set_post_terms($post_id, [$country_term_id], 'company_country');
            if (is_wp_error($result)) {
                throw new Exception("Failed to assign country taxonomy: " . $result->get_error_message());
            }
        }

        // Add category taxonomies
        if (!empty($data['taxonomy_company_category'])) {
            $categories = explode(',', $data['taxonomy_company_category']);
            $category_ids = [];

            foreach ($categories as $category) {
                $category = trim($category);
                if (empty($category)) continue;

                // Check if term already exists
                $existing_term = get_term_by('name', $category, 'company_category');

                if ($existing_term) {
                    // Use existing term
                    $category_ids[] = $existing_term->term_id;
                } else {
                    // Create new term
                    $cat_term = wp_insert_term($category, 'company_category');
                    if (is_wp_error($cat_term)) {
                        throw new Exception("Failed to create category term '{$category}': " . $cat_term->get_error_message());
                    }
                    $category_ids[] = $cat_term['term_id'];
                }
            }

            if (!empty($category_ids)) {
                $result = wp_set_post_terms($post_id, $category_ids, 'company_category');
                if (is_wp_error($result)) {
                    throw new Exception("Failed to assign category taxonomies: " . $result->get_error_message());
                }
            }
        }
    }

    /**
     * Count CSV rows for progress bar
     */
    private function countCsvRows($file)
    {
        $lines = 0;
        $handle = fopen($file, 'r');
        while (fgets($handle) !== false) {
            $lines++;
        }
        fclose($handle);
        return $lines;
    }

    /**
     * Validate file path to prevent path traversal attacks
     *
     * @param string $file The file path to validate
     * @return string|false The validated real path or false if invalid
     */
    private function validateFilePath($file)
    {
        // Get the real path (resolves symlinks and removes ../ sequences)
        $real_path = realpath(dirname($file));

        // If realpath returns false, the directory doesn't exist
        if ($real_path === false) {
            return false;
        }

        // Reconstruct the full file path with validated directory
        $real_path = $real_path . '/' . basename($file);

        // Get the allowed base directory (WordPress root)
        $allowed_base = realpath(ABSPATH);

        if ($allowed_base === false) {
            return false;
        }

        // Ensure the file path starts with the allowed base directory
        if (!str_starts_with($real_path, $allowed_base)) {
            return false;
        }

        return $real_path;
    }
}
