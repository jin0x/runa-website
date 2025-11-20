@php
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;

  // Set theme context for FacetWP
  set_query_var('company_directory_theme', $theme);
@endphp

{{-- Choose between FacetWP and legacy implementation --}}
@if($use_facetwp && function_exists('FWP'))
  @include('blocks.company-directory-facetwp')
@else
  {{-- Legacy implementation below --}}
@php
  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Background color handled by section component via $themeVariant

  // Text colors based on theme
  $textColor = $themeVariant === ThemeVariant::DARK ? 'text-white' : 'text-black';
  $borderColor = $themeVariant === ThemeVariant::DARK ? 'border-gray-700' : 'border-gray-200';

  // Input/select field colors and SVG Decoration for dropdown

  $form_classes = 'w-full px-6 py-6 pr-12 flex items-center justify-between gap-3 '.($themeVariant === ThemeVariant::DARK ? 'bg-gray-900' : 'bg-gray-50').' rounded-md appearance-none focus:outline-none';

  $svg_dropdown = '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 absolute top-1/2 -translate-y-1/2 pointer-events-none '.$textColor.' right-6"><g clip-path="url(#clip0_3437_54696)"><path d="M19.3435 9.31081L12.2725 16.3819L5.20139 9.31081" stroke="currentColor" stroke-width="2"/></g><defs><clipPath id="clip0_3437_54696"><rect width="24" height="24" fill="white" transform="translate(0.333008 0.5)"/></clipPath></defs></svg>';
  $svg_search = '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 absolute top-1/2 -translate-y-1/2 pointer-events-none '.$textColor.' right-6"><g clip-path="url(#clip0_3437_42119)"><path d="M15.5 14.5H14.71L14.43 14.23C15.41 13.09 16 11.61 16 10C16 6.41 13.09 3.5 9.5 3.5C5.91 3.5 3 6.41 3 10C3 13.59 5.91 16.5 9.5 16.5C11.11 16.5 12.59 15.91 13.73 14.93L14 15.21V16L19 20.99L20.49 19.5L15.5 14.5ZM9.5 14.5C7.01 14.5 5 12.49 5 10C5 7.51 7.01 5.5 9.5 5.5C11.99 5.5 14 7.51 14 10C14 12.49 11.99 14.5 9.5 14.5Z" fill="currentColor"/></g><defs><clipPath id="clip0_3437_42119"><rect width="24" height="24" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>';
  $svg_reset = '<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 '.$textColor.' transition-transform duration-500 ease-in-out group-hover:rotate-360"><g clip-path="url(#clip0_3437_54923)"><path d="M14.7085 5.79183C13.5001 4.5835 11.8418 3.8335 10.0001 3.8335C6.3168 3.8335 3.3418 6.81683 3.3418 10.5002C3.3418 14.1835 6.3168 17.1668 10.0001 17.1668C13.1085 17.1668 15.7001 15.0418 16.4418 12.1668H14.7085C14.0251 14.1085 12.1751 15.5002 10.0001 15.5002C7.2418 15.5002 5.00013 13.2585 5.00013 10.5002C5.00013 7.74183 7.2418 5.50016 10.0001 5.50016C11.3835 5.50016 12.6168 6.07516 13.5168 6.9835L10.8335 9.66683H16.6668V3.8335L14.7085 5.79183Z" fill="currentColor"/></g><defs><clipPath id="clip0_3437_54923"><rect width="20" height="20" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>';
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
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
      data-company-directory
      class="company-directory-block"
      data-theme="{{ $theme }}"
    >
      {{-- Filter Section --}}
      <div class="mb-8">
        <form data-filters-form class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_auto] gap-4 items-end">

          {{-- Country Filter --}}
          <div class="relative">
            <select
              id="{{ $block_id }}-country"
              name="country"
              data-country-filter
              class="{{ $textColor }} {{$form_classes}}"
              aria-label="Filter companies by country"
            >
              <option value="">All Countries</option>
              @if(!empty($countries))
                @foreach($countries as $country)
                  <option value="{{ $country->slug }}">{{ $country->name }} ({{ $country->count }})</option>
                @endforeach
              @endif
            </select>
            {!! $svg_dropdown !!}
          </div>

          {{-- Category Filter --}}
          <div class="relative">
            <select
              id="{{ $block_id }}-category"
              name="category"
              data-category-filter
              class="{{ $textColor }} {{$form_classes}}"
              aria-label="Filter companies by category"
            >
              <option value="">All Categories</option>
              @if(!empty($categories))
                @foreach($categories as $category)
                  <option value="{{ $category->slug }}">{{ $category->name }} ({{ $category->count }})</option>
                @endforeach
              @endif
            </select>
            {!! $svg_dropdown !!}
          </div>

          {{-- Search Input --}}
          <div class="relative">
            <input
              type="text"
              id="{{ $block_id }}-search"
              name="search"
              data-search-filter
              placeholder="Search"
              class="{{ $textColor }} {{$form_classes}}"
              aria-label="Search"
            />
            {!! $svg_search !!}
          </div>

          {{-- Clear Filters --}}
          <div class="mt-4">
            <button
              data-clear-filters
              class="group text-sm {{ $textColor }} w-full px-6 py-6 pr-12 flex items-center justify-between gap-3 transition-colors duration-200"
              aria-label="Clear all filters"
            >
              Reset Filter
              {!! $svg_reset !!}
            </button>
          </div>
        </form>
      </div>

      {{-- Results Count --}}
      <div class="mb-4">
        <p class="text-sm {{ $textColor }}">
          Showing <span data-results-count>{{ count($companies) }}</span> companies
        </p>
      </div>

      {{-- Companies Table --}}
      <div class="overflow-x-auto" data-table-container>
        <table class="w-full shadow-lg rounded-lg overflow-hidden">
          <thead class="green-horizontal-gradient">
            <tr>
              <th class="px-6 py-4 text-left text-sm font-semibold text-black capitalize tracking-wider">
                Name
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
                Category
              </th>
            </tr>
          </thead>
          <tbody data-companies-table class="divide-y {{ $themeVariant === ThemeVariant::DARK ? 'divide-neutral-0-32' : 'divide-neutral-dark-10' }} {{ $borderColor }}">
            @if(!empty($companies))
              @foreach($companies as $company)
                <tr
                  class="company-row hover:{{ $themeVariant === ThemeVariant::DARK ? 'bg-gray-800' : 'bg-gray-50' }} transition-colors duration-200"
                  data-company-name="{{ esc_attr(strtolower($company['title'])) }}"
                  data-country="{{ esc_attr(!empty($company['countries']) ? implode(',', array_map('strtolower', $company['countries'])) : '') }}"
                  data-categories="{{ esc_attr(!empty($company['categories']) ? implode(',', array_map('strtolower', $company['categories'])) : '') }}"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium {{ $textColor }}">
                      {{ $company['title'] }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                    {{ $company['country_name'] ?? 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                    @if(!empty($company['country_code']))
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-green-neon text-black">
                        {{ strtoupper($company['country_code']) }}
                      </span>
                    @else
                      <span class="text-gray-500">N/A</span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                    @if(!empty($company['company_currency']))
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ strtoupper($company['company_currency']) }}
                      </span>
                    @else
                      <span class="text-gray-500">N/A</span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm {{ $textColor }}">
                    @if(!empty($company['categories']))
                      <div class="flex flex-wrap gap-1">
                        @foreach($company['categories'] as $category)
                          <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-200 text-gray-800">
                            {{ $category }}
                          </span>
                        @endforeach
                      </div>
                    @else
                      <span class="text-gray-500">N/A</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                  No companies found.
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      {{-- No Results Message --}}
      <div data-no-results class="hidden text-center py-8">
        <p class="text-lg {{ $textColor }} mb-2">No companies found matching your filters.</p>
        <p class="text-sm text-gray-500">Try adjusting your search criteria or clearing all filters.</p>
      </div>
    </div>
  </x-container>
</x-section>

{{-- Modern JavaScript for filtering with proper scoping --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize each company directory block instance
    document.querySelectorAll('[data-company-directory]').forEach(function(block) {
        new CompanyDirectoryFilter(block);
    });
});

/**
 * Company Directory Filter Class
 * Handles filtering for individual block instances
 */
function CompanyDirectoryFilter(blockElement) {
    this.block = blockElement;
    this.form = this.block.querySelector('[data-filters-form]');
    this.countryFilter = this.block.querySelector('[data-country-filter]');
    this.categoryFilter = this.block.querySelector('[data-category-filter]');
    this.searchFilter = this.block.querySelector('[data-search-filter]');
    this.clearButton = this.block.querySelector('[data-clear-filters]');
    this.tableContainer = this.block.querySelector('[data-table-container]');
    this.tableBody = this.block.querySelector('[data-companies-table]');
    this.resultsCount = this.block.querySelector('[data-results-count]');
    this.noResults = this.block.querySelector('[data-no-results]');
    this.companyRows = this.block.querySelectorAll('.company-row');

    // Bind methods to preserve context
    this.filterCompanies = this.filterCompanies.bind(this);
    this.clearFilters = this.clearFilters.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);

    // Initialize event listeners
    this.init();
}

CompanyDirectoryFilter.prototype.init = function() {
    // Prevent form submission
    if (this.form) {
        this.form.addEventListener('submit', this.handleSubmit);
    }

    // Add change/input listeners with debouncing for search
    if (this.countryFilter) {
        this.countryFilter.addEventListener('change', this.filterCompanies);
    }

    if (this.categoryFilter) {
        this.categoryFilter.addEventListener('change', this.filterCompanies);
    }

    if (this.searchFilter) {
        // Debounce search input for better performance
        let searchTimeout;
        this.searchFilter.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(this.filterCompanies, 300);
        });
    }

    // Clear filters button
    if (this.clearButton) {
        this.clearButton.addEventListener('click', this.clearFilters);
    }
};

CompanyDirectoryFilter.prototype.handleSubmit = function(e) {
    e.preventDefault();
    this.filterCompanies();
};

CompanyDirectoryFilter.prototype.filterCompanies = function() {
    const countryValue = this.countryFilter ? this.countryFilter.value.toLowerCase() : '';
    const categoryValue = this.categoryFilter ? this.categoryFilter.value.toLowerCase() : '';
    const searchValue = this.searchFilter ? this.searchFilter.value.toLowerCase().trim() : '';

    let visibleCount = 0;

    // Filter each company row
    this.companyRows.forEach((row) => {
        const companyName = row.dataset.companyName || '';
        const companyCountries = row.dataset.country || '';
        const companyCategories = row.dataset.categories || '';

        let showRow = true;

        // Country filter
        if (countryValue && !companyCountries.includes(countryValue)) {
            showRow = false;
        }

        // Category filter
        if (categoryValue && !companyCategories.includes(categoryValue)) {
            showRow = false;
        }

        // Search filter
        if (searchValue && !companyName.includes(searchValue)) {
            showRow = false;
        }

        // Show/hide row with smooth transition
        if (showRow) {
            row.style.display = '';
            row.setAttribute('aria-hidden', 'false');
            visibleCount++;
        } else {
            row.style.display = 'none';
            row.setAttribute('aria-hidden', 'true');
        }
    });

    // Update results count
    if (this.resultsCount) {
        this.resultsCount.textContent = visibleCount;
    }

    // Show/hide no results message and table
    if (visibleCount === 0) {
        if (this.noResults) {
            this.noResults.classList.remove('hidden');
            this.noResults.setAttribute('aria-hidden', 'false');
        }
        if (this.tableContainer) {
            this.tableContainer.classList.add('hidden');
            this.tableContainer.setAttribute('aria-hidden', 'true');
        }
    } else {
        if (this.noResults) {
            this.noResults.classList.add('hidden');
            this.noResults.setAttribute('aria-hidden', 'true');
        }
        if (this.tableContainer) {
            this.tableContainer.classList.remove('hidden');
            this.tableContainer.setAttribute('aria-hidden', 'false');
        }
    }

    // Announce filter results to screen readers
    this.announceFilterResults(visibleCount);
};

CompanyDirectoryFilter.prototype.clearFilters = function() {
    if (this.countryFilter) this.countryFilter.value = '';
    if (this.categoryFilter) this.categoryFilter.value = '';
    if (this.searchFilter) this.searchFilter.value = '';

    this.filterCompanies();

    // Focus search input for better UX
    if (this.searchFilter) {
        this.searchFilter.focus();
    }
};

CompanyDirectoryFilter.prototype.announceFilterResults = function(count) {
    // Create or update ARIA live region for screen readers
    let liveRegion = this.block.querySelector('[data-filter-announcements]');

    if (!liveRegion) {
        liveRegion = document.createElement('div');
        liveRegion.setAttribute('data-filter-announcements', '');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        this.block.appendChild(liveRegion);
    }

    const message = count === 0 ?
        'No companies match your filters.' :
        `${count} company${count === 1 ? '' : 's'} found.`;

    liveRegion.textContent = message;
};
</script>

@endif
