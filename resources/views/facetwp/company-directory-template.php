<?php
/**
 * FacetWP Template: Company Directory
 *
 * This template is used by FacetWP to display the company directory results.
 * It replaces the custom JavaScript filtering with server-side filtering.
 */

// Get the current theme from the page context
$theme = get_query_var('company_directory_theme', 'light');
$textColor = $theme === 'dark' ? 'text-white' : 'text-black';
$borderColor = $theme === 'dark' ? 'border-gray-700' : 'border-gray-200';
$hoverColor = $theme === 'dark' ? 'bg-gray-800' : 'bg-gray-50';
?>

<table class="w-full <?php echo $theme === 'dark' ? 'bg-gray-900' : 'bg-white'; ?> shadow-lg rounded-lg overflow-hidden">
  <thead class="<?php echo $theme === 'dark' ? 'bg-gray-800' : 'bg-gray-50'; ?>">
    <tr>
      <th class="px-6 py-4 text-left text-sm font-semibold <?php echo $textColor; ?> uppercase tracking-wider">
        Company Name
      </th>
      <th class="px-6 py-4 text-left text-sm font-semibold <?php echo $textColor; ?> uppercase tracking-wider">
        Country
      </th>
      <th class="px-6 py-4 text-left text-sm font-semibold <?php echo $textColor; ?> uppercase tracking-wider">
        Country Code
      </th>
      <th class="px-6 py-4 text-left text-sm font-semibold <?php echo $textColor; ?> uppercase tracking-wider">
        Categories
      </th>
    </tr>
  </thead>
  <tbody class="divide-y <?php echo $borderColor; ?>">
    <?php
    if (have_posts()) :
      while (have_posts()) : the_post();
        // Get ACF fields
        $company_slug = get_field('company_slug');
        $country_code = get_field('country_code');
        $country_name = get_field('country_name');

        // Get taxonomies
        $country_terms = get_the_terms(get_the_ID(), 'company_country');
        $category_terms = get_the_terms(get_the_ID(), 'company_category');
    ?>
    <tr class="company-row hover:<?php echo $hoverColor; ?> transition-colors duration-200">
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium <?php echo $textColor; ?>">
          <?php the_title(); ?>
        </div>
        <?php if (!empty($company_slug)) : ?>
          <div class="text-xs text-gray-500">
            <?php echo esc_html($company_slug); ?>
          </div>
        <?php endif; ?>
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $textColor; ?>">
        <?php echo $country_name ? esc_html($country_name) : 'N/A'; ?>
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $textColor; ?>">
        <?php if (!empty($country_code)) : ?>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-green-neon text-black">
            <?php echo strtoupper(esc_html($country_code)); ?>
          </span>
        <?php else : ?>
          <span class="text-gray-500">N/A</span>
        <?php endif; ?>
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo $textColor; ?>">
        <?php if ($category_terms && !is_wp_error($category_terms)) : ?>
          <div class="flex flex-wrap gap-1">
            <?php foreach ($category_terms as $category) : ?>
              <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-200 text-gray-800">
                <?php echo esc_html($category->name); ?>
              </span>
            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <span class="text-gray-500">N/A</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php
      endwhile;
      wp_reset_postdata();
    else :
    ?>
    <tr>
      <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
        No companies found matching your filters.
      </td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>