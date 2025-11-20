<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use League\ISO3166\ISO3166;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class CompanyDirectoryBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Company Directory';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A filterable directory of companies with search and taxonomy filters.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'runa';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'building';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['company', 'directory', 'filter', 'search', 'table'];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = 'full';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'anchor' => true,
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        // Check if FacetWP is enabled for this block
        $use_facetwp = $this->getUseFacetWP();

        $data = [
            // Block Instance Data
            'block_id' => $this->getBlockId(),

            // Section Heading Fields
            'section_eyebrow' => $this->getSectionEyebrow(),
            'section_title' => $this->getSectionTitle(),
            'section_description' => $this->getSectionDescription(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'use_facetwp' => $use_facetwp,
        ];

        // Only load company data if not using FacetWP
        if (!$use_facetwp) {
            $data['companies'] = $this->getCompanies();
            $data['countries'] = $this->getCountries();
            $data['categories'] = $this->getCategories();
        }

        return $data;
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $companyDirectory = Builder::make('company_directory');

        $companyDirectory
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addTrueFalse('use_facetwp', [
                'label' => 'Use FacetWP for Filtering',
                'message' => 'Enable FacetWP for better performance with large datasets',
                'default_value' => 1,
                'ui' => 1,
                'instructions' => 'FacetWP provides server-side filtering for better performance with 4K+ records. Disable to use legacy client-side filtering (limited to 500 records).',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $companyDirectory->build();
    }

    /**
     * Section heading getters
     */
    public function getSectionEyebrow()
    {
        return get_field('eyebrow');
    }

    public function getSectionTitle()
    {
        return get_field('heading');
    }

    public function getSectionDescription()
    {
        return get_field('subtitle');
    }

    /**
     * Get all companies with performance optimizations
     */
    public function getCompanies()
    {
        // Use transient caching for companies data
        $cache_key = 'company_directory_data_v2';
        $companies_data = get_transient($cache_key);

        if (false === $companies_data) {
            // Note: This is only used for legacy mode when FacetWP is disabled
            // FacetWP handles all querying when enabled
            $companies = get_posts([
                'post_type' => 'company',
                'numberposts' => 500, // Limit for legacy mode performance
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'company_slug',
                        'compare' => 'EXISTS',
                    ],
                ],
            ]);

            $companies_data = [];

            foreach ($companies as $company) {
                // Get ACF fields
                $company_slug = get_field('company_slug', $company->ID);
                $country_code = get_field('country_code', $company->ID);
                $country_name = get_field('country_name', $company->ID);

                // Get currency field - it returns an array with 'value', 'label', 'disabled' keys
                $currency_field = get_field('company_currency', $company->ID);
                $company_currency = is_array($currency_field) && isset($currency_field['value']) ? $currency_field['value'] : $currency_field;

                // Get taxonomies
                $country_terms = get_the_terms($company->ID, 'company_country');
                $category_terms = get_the_terms($company->ID, 'company_category');

                $companies_data[] = [
                    'id' => $company->ID,
                    'title' => $company->post_title,
                    'slug' => $company_slug,
                    'country_code' => $country_code,
                    'country_name' => $country_name,
                    'company_currency' => $company_currency,
                    'countries' => $country_terms ? array_map(fn($term) => $term->name, $country_terms) : [],
                    'categories' => $category_terms ? array_map(fn($term) => $term->name, $category_terms) : [],
                ];
            }

            // Cache for 15 minutes
            set_transient($cache_key, $companies_data, 15 * MINUTE_IN_SECONDS);
        }

        return $companies_data;
    }

    /**
     * Get all country taxonomy terms with caching
     */
    public function getCountries()
    {
        $cache_key = 'company_countries_v1';
        $terms = get_transient($cache_key);

        if (false === $terms) {
            $terms = get_terms([
                'taxonomy' => 'company_country',
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC',
            ]);

            if (is_wp_error($terms)) {
                $terms = [];
            }

            // Cache for 30 minutes
            set_transient($cache_key, $terms, 30 * MINUTE_IN_SECONDS);
        }

        return $terms;
    }

    /**
     * Get all category taxonomy terms with caching
     */
    public function getCategories()
    {
        $cache_key = 'company_categories_v1';
        $terms = get_transient($cache_key);

        if (false === $terms) {
            $terms = get_terms([
                'taxonomy' => 'company_category',
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC',
            ]);

            if (is_wp_error($terms)) {
                $terms = [];
            }

            // Cache for 30 minutes
            set_transient($cache_key, $terms, 30 * MINUTE_IN_SECONDS);
        }

        return $terms;
    }

    /**
     * Get unique block ID for multiple instance support
     */
    public function getBlockId()
    {
        // Use block ID if available, otherwise generate unique ID
        if (!empty($this->block->id)) {
            return 'company-directory-' . $this->block->id;
        }

        // Fallback to unique ID based on current time and random number
        return 'company-directory-' . uniqid();
    }

    public function getSectionSize()
    {
        return get_field('section_size') ?: 'md';
    }

    public function getTheme()
    {
        return get_field('theme') ?: 'light';
    }

    public function getUseFacetWP()
    {
        // Default to true if FacetWP is available, false otherwise
        $default = function_exists('FWP') ? true : false;
        return get_field('use_facetwp') !== null ? get_field('use_facetwp') : $default;
    }

    /**
     * Get ISO 2-letter country code from country name
     *
     * @param string $countryName
     * @return string|null
     */
    public static function getCountryCode($countryName)
    {
        if (empty($countryName)) {
            return null;
        }

        try {
            $iso3166 = new ISO3166;

            // Common country name variations mapping
            $nameMapping = [
                'United States' => 'United States of America',
                'USA' => 'United States of America',
                'UK' => 'United Kingdom of Great Britain and Northern Ireland',
                'Britain' => 'United Kingdom of Great Britain and Northern Ireland',
                'Russia' => 'Russian Federation',
                'South Korea' => 'Korea, Republic of',
                'North Korea' => 'Korea, Democratic People\'s Republic of',
                'Iran' => 'Iran, Islamic Republic of',
                'Syria' => 'Syrian Arab Republic',
                'Venezuela' => 'Venezuela, Bolivarian Republic of',
                'Bolivia' => 'Bolivia, Plurinational State of',
                'Tanzania' => 'Tanzania, United Republic of',
                'Vietnam' => 'Viet Nam',
                'Moldova' => 'Moldova, Republic of',
                'Macedonia' => 'North Macedonia',
            ];

            // Try mapped name first
            $searchName = $nameMapping[$countryName] ?? $countryName;

            // Attempt to find country by name
            $country = $iso3166->name($searchName);
            return $country['alpha2'];

        } catch (\Exception $e) {
            // If lookup fails, try a few common variations
            $commonVariations = [
                trim($countryName),
                ucwords(strtolower($countryName)),
                $countryName . ' Republic',
                'Republic of ' . $countryName,
            ];

            foreach ($commonVariations as $variation) {
                try {
                    $country = $iso3166->name($variation);
                    return $country['alpha2'];
                } catch (\Exception $inner) {
                    continue;
                }
            }

            // Log the failed lookup for debugging (sanitize to prevent log injection)
            error_log("Country code lookup failed for: " . str_replace(["\n", "\r"], '', $countryName));
            return null;
        }
    }
}