@php
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Text colors based on theme
  $textColor = $themeVariant === ThemeVariant::DARK ? 'text-white' : 'text-black';
  $borderColor = $themeVariant === ThemeVariant::DARK ? 'border-gray-700' : 'border-gray-200';
  $form_classes = 'w-full px-6 py-6 flex items-center justify-between gap-3 '.($themeVariant === ThemeVariant::DARK ? 'bg-gray-900' : 'bg-gray-50').' rounded-md appearance-none focus:outline-none';
  $svg_reset = '<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 '.$textColor.' transition-transform duration-500 ease-in-out group-hover:rotate-360"><g clip-path="url(#clip0_3437_54923)"><path d="M14.7085 5.79183C13.5001 4.5835 11.8418 3.8335 10.0001 3.8335C6.3168 3.8335 3.3418 6.81683 3.3418 10.5002C3.3418 14.1835 6.3168 17.1668 10.0001 17.1668C13.1085 17.1668 15.7001 15.0418 16.4418 12.1668H14.7085C14.0251 14.1085 12.1751 15.5002 10.0001 15.5002C7.2418 15.5002 5.00013 13.2585 5.00013 10.5002C5.00013 7.74183 7.2418 5.50016 10.0001 5.50016C11.3835 5.50016 12.6168 6.07516 13.5168 6.9835L10.8335 9.66683H16.6668V3.8335L14.7085 5.79183Z" fill="currentColor"/></g><defs><clipPath id="clip0_3437_54923"><rect width="20" height="20" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>';
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">

  {{-- Section Heading --}}
  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$sectionHeadingVariant"
      classes="mb-12"
    />
  @endif

  <x-container>

    {{-- Section Heading --}}
    @if($section_eyebrow || $section_title || $section_description)
      <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_title"
        :subtitle="$section_description"
        :variant="$sectionHeadingVariant"
        classes="mb-12"
      />
    @endif

    {{-- Company Directory Container with unique block ID --}}
    <div
      id="{{ $block_id }}"
      data-company-directory-facetwp
      class="company-directory-facetwp-block"
      data-theme="{{ $theme }}"
    >
      {{-- Filter Section --}}
      <div class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_auto] gap-4 items-end">
          {{-- Country Filter --}}
          <div class="relative">
            <div class="{{ $textColor }} {{$form_classes}}">
              <div class="w-full facetwp-facet-container">
                {!! facetwp_display('facet', 'company_country') !!}
              </div>
            </div>
          </div>
          {{-- Category Filter --}}
          <div class="relative">
            <div class="{{ $textColor }} {{$form_classes}}">
              <div class="w-full facetwp-facet-container">
                {!! facetwp_display('facet', 'company_category') !!}
              </div>
            </div>
          </div>
          {{-- Search Input --}}
          <div class="relative">
            <div class="{{ $textColor }} {{$form_classes}}">
              <div class="w-full facetwp-facet-container">
                {!! facetwp_display('facet', 'company_search') !!}
              </div>
            </div>
          </div>
          {{-- Clear Filters Button --}}
          <div class="mt-4">
            <button
              type="button"
              onclick="FWP.reset()"
              class="group text-sm {{ $textColor }} w-full px-6 py-6 pr-12 flex items-center justify-between gap-3 transition-colors duration-200"
              aria-label="Clear all filters"
            >
              Reset Filter
              {!! $svg_reset !!}
            </button>
          </div>
        </div>
      </div>

      {{-- Results Count --}}
      <div class="mb-4">
        <p class="text-sm {{ $textColor }}">
          {!! facetwp_display('counts') !!}
        </p>
      </div>

      {{-- Companies Table --}}
      <div class="overflow-x-auto">
        <div class="facetwp-template">
          @php
            // Custom WP_Query for company directory with FacetWP integration
            $query_args = [
              'post_status'    => 'publish',
              'post_type'      => 'company',
              'posts_per_page' => 50,
              'orderby'        => 'title',
              'order'          => 'ASC',
              'facetwp'        => true, // Let FacetWP handle the filtering
              'meta_query'     => [
                [
                  'key' => 'company_slug',
                  'compare' => 'EXISTS',
                ],
              ],
            ];

            $companies_query = new WP_Query($query_args);
          @endphp

          <table class="w-full {{ $themeVariant === ThemeVariant::DARK ? 'bg-gray-900' : 'bg-white' }} shadow-lg rounded-lg overflow-hidden">
            <thead class="green-horizontal-gradient">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-black capitalize tracking-wider">
                  Company Name
                </th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-black capitalize tracking-wider">
                  Country
                </th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-black capitalize tracking-wider">
                  Country Code
                </th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-black capitalize tracking-wider">
                  Currency
                </th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-black capitalize tracking-wider">
                  Categories
                </th>
              </tr>
            </thead>
            <tbody class="divide-y {{ $themeVariant === ThemeVariant::DARK ? 'divide-neutral-0-32' : 'divide-neutral-dark-10' }} {{ $borderColor }}">
              @if($companies_query->have_posts())
                @while($companies_query->have_posts())
                  @php
                    $companies_query->the_post();

                    // Get ACF fields
                    $company_slug = get_field('company_slug');
                    $country_code = get_field('country_code');
                    $country_name = get_field('country_name');
                    $company_currency = get_field('company_currency');

                    // Get taxonomies
                    $country_terms = get_the_terms(get_the_ID(), 'company_country');
                    $category_terms = get_the_terms(get_the_ID(), 'company_category');
                  @endphp

                  <tr class="company-row hover:{{ $themeVariant === ThemeVariant::DARK ? 'bg-gray-800' : 'bg-gray-50' }} transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium {{ $textColor }}">
                        {!! get_the_title() !!}
                      </div>
                      @if(!empty($company_slug))
                        <div class="text-xs text-gray-500">
                          {{ $company_slug }}
                        </div>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                      @if($country_terms && !is_wp_error($country_terms))
                        {!! $country_terms[0]->name !!}
                      @elseif($country_name)
                        {{ $country_name }}
                      @else
                        N/A
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                      @php
                        // Get country code - try ACF field first, then derive from country name
                        $display_country_code = $country_code;

                        if (empty($display_country_code) && $country_terms && !is_wp_error($country_terms)) {
                          // Call static method - no need to instantiate class
                          $display_country_code = \App\Blocks\CompanyDirectoryBlock::getCountryCode($country_terms[0]->name);
                        }
                      @endphp

                      @if(!empty($display_country_code))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-green-neon text-black">
                          {{ strtoupper($display_country_code) }}
                        </span>
                      @else
                        <span class="text-gray-500">N/A</span>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                      @if(!empty($company_currency))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          {{ strtoupper($company_currency) }}
                        </span>
                      @else
                        <span class="text-gray-500">N/A</span>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                      @if($category_terms && !is_wp_error($category_terms))
                        <div class="flex flex-wrap gap-1">
                          @foreach($category_terms as $category)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-200 text-gray-800">
                              {!! $category->name !!}
                            </span>
                          @endforeach
                        </div>
                      @else
                        <span class="text-gray-500">N/A</span>
                      @endif
                    </td>
                  </tr>
                @endwhile
                @php(wp_reset_postdata())
              @else
                <tr>
                  <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                    No companies found matching your filters.
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>

      {{-- Pagination --}}
      <div class="mt-8 flex justify-center">
        {!! facetwp_display('pager') !!}
      </div>
    </div>
  </x-container>
</x-section>

{{-- FacetWP Integration JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize FacetWP for this block instance
    const block = document.querySelector('[data-company-directory-facetwp]');
    if (block) {
        new CompanyDirectoryFacetWP(block);
    }
});

/**
 * Company Directory FacetWP Integration Class
 * Handles styling and UX improvements for FacetWP
 */
function CompanyDirectoryFacetWP(blockElement) {
    this.block = blockElement;
    this.theme = this.block.dataset.theme || 'light';

    // Initialize styling and event listeners
    this.init();
}

CompanyDirectoryFacetWP.prototype.init = function() {
    // Style FacetWP elements to match theme
    this.styleFacetWPElements();

    // Set up FacetWP event listeners
    this.setupFacetWPEvents();
};

CompanyDirectoryFacetWP.prototype.styleFacetWPElements = function() {
    const isDark = this.theme === 'dark';
    const borderColor = isDark ? 'border-gray-700' : 'border-gray-200';
    const bgColor = isDark ? 'bg-gray-900' : 'bg-gray-50';
    const textColor = isDark ? 'text-white' : 'text-black';
    const placeholderColor = isDark ? 'placeholder-gray-400' : 'placeholder-gray-500';

    // Style dropdowns
    const selects = this.block.querySelectorAll('.facetwp-dropdown select');
    selects.forEach(select => {
        select.className = `w-full px-0 py-0 border-0 bg-transparent ${textColor} focus:outline-none focus:ring-0 focus:border-transparent`;
    });

    // Style search input
    const searchInputs = this.block.querySelectorAll('.facetwp-search input');
    searchInputs.forEach(input => {
        input.className = `w-full px-0 py-0 border-0 bg-transparent ${textColor} ${placeholderColor} focus:outline-none focus:ring-0 focus:border-transparent`;
    });
};

CompanyDirectoryFacetWP.prototype.setupFacetWPEvents = function() {
    // Set default country to US on initial load
    document.addEventListener('facetwp-loaded', function() {
        // Check if this is the initial load (no filters set yet)
        if (typeof FWP.facets.company_country === 'undefined' || FWP.facets.company_country.length === 0) {
            // Set default country to 'united-states' (FacetWP uses term slug)
            FWP.facets.company_country = ['united-states'];
            FWP.refresh();
        }

        // Replace default "Any" label with placeholders
        this.replaceDropdownPlaceholders();

        this.removeLoadingState();
        this.styleFacetWPElements(); // Re-apply styles after AJAX
    }.bind(this));

    // Add loading state when filtering
    document.addEventListener('facetwp-refresh', function() {
        if (FWP.loaded) {
            this.addLoadingState();
        }
    }.bind(this));
};

CompanyDirectoryFacetWP.prototype.addLoadingState = function() {
    const template = this.block.querySelector('.facetwp-template');
    if (template) {
        template.style.opacity = '0.5';
        template.style.pointerEvents = 'none';
    }
};

CompanyDirectoryFacetWP.prototype.removeLoadingState = function() {
    const template = this.block.querySelector('.facetwp-template');
    if (template) {
        template.style.opacity = '1';
        template.style.pointerEvents = 'auto';
    }
};
CompanyDirectoryFacetWP.prototype.replaceDropdownPlaceholders = function() {
    // removing "any"
    const countryPlaceholder = 'Country';
    const categoryPlaceholder = 'Category';
    const searchPlaceholder = 'Search';

    // pais
    const countrySelect = this.block.querySelector('.facetwp-facet-company_country select option[value=""]');
    if (countrySelect && countrySelect.textContent.trim().toLowerCase() === 'any') {
        countrySelect.textContent = countryPlaceholder;
    }

    // category
    const categorySelect = this.block.querySelector('.facetwp-facet-company_category select option[value=""]');
    if (categorySelect && categorySelect.textContent.trim().toLowerCase() === 'any') {
        categorySelect.textContent = categoryPlaceholder;
    }

    // search
    const searchInput = this.block.querySelector('.facetwp-facet-company_search input');
    if (searchInput) {
        const current = (searchInput.placeholder || '').trim().toLowerCase();
        if (current === 'enter keywords') {
            searchInput.placeholder = searchPlaceholder;
        }
    }
};
</script>

{{-- FacetWP Styling --}}
<style>
/* Custom styling for FacetWP elements */
.facetwp-facet-container .facetwp-dropdown {
    width: 100%;
}

.facetwp-facet-container .facetwp-search {
    width: 100%;
}

.facetwp-template {
    transition: opacity 0.2s ease-out;
}

/* Pagination styling */
.facetwp-pager {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}

.facetwp-pager a,
.facetwp-pager .selected {
    padding: 0.5rem 1rem;
    border: 1px solid;
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.facetwp-pager a {
    @apply border-gray-300 text-gray-700 hover:bg-gray-50;
}

.facetwp-pager .selected {
    @apply border-primary-green-neon bg-primary-green-neon text-black;
}

/* Results count styling */
.facetwp-counts {
    font-weight: 400;
}

/* Remove margin */
.facetwp-facet {
  margin-bottom: 0!important;
}
</style>
