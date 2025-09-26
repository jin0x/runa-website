@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ThemeVariant;

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
  $sectionSizeValue = match ($section_size) {
      'none' => SectionSize::NONE,
      'xs' => SectionSize::XSMALL,
      'sm' => SectionSize::SMALL,
      'md' => SectionSize::MEDIUM,
      'lg' => SectionSize::LARGE,
      'xl' => SectionSize::XLARGE,
      default => SectionSize::MEDIUM,
  };

  // Convert theme string to ThemeVariant enum
  $themeVariant = $theme === 'dark' ? ThemeVariant::DARK : ThemeVariant::LIGHT;

  // Set background color based on theme
  $bgColor = match ($theme) {
      'light' => 'bg-white',
      default => 'bg-black',
  };

  // Text colors based on theme
  $textColor = $theme === 'dark' ? 'text-white' : 'text-black';
  $borderColor = $theme === 'dark' ? 'border-gray-700' : 'border-gray-200';
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">

  {{-- Section Heading --}}
  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$themeVariant"
      classes="mb-12"
    />
  @endif

  <x-container>
    {{-- Company Directory Container with unique block ID --}}
    <div
      id="{{ $block_id }}"
      data-company-directory
      class="company-directory-block"
      data-theme="{{ $theme }}"
    >
      {{-- Filter Section --}}
      <div class="mb-8 p-6 rounded-lg {{ $theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50' }} {{ $borderColor }} border">
        <form data-filters-form class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

          {{-- Country Filter --}}
          <div>
            <label for="{{ $block_id }}-country" class="block text-sm font-medium mb-2 {{ $textColor }}">
              Filter by Country
            </label>
            <select
              id="{{ $block_id }}-country"
              name="country"
              data-country-filter
              class="w-full px-3 py-2 border {{ $borderColor }} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent {{ $theme === 'dark' ? 'bg-gray-800 text-white' : 'bg-white text-black' }}"
              aria-label="Filter companies by country"
            >
              <option value="">All Countries</option>
              @if(!empty($countries))
                @foreach($countries as $country)
                  <option value="{{ $country->slug }}">{{ $country->name }} ({{ $country->count }})</option>
                @endforeach
              @endif
            </select>
          </div>

          {{-- Category Filter --}}
          <div>
            <label for="{{ $block_id }}-category" class="block text-sm font-medium mb-2 {{ $textColor }}">
              Filter by Category
            </label>
            <select
              id="{{ $block_id }}-category"
              name="category"
              data-category-filter
              class="w-full px-3 py-2 border {{ $borderColor }} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent {{ $theme === 'dark' ? 'bg-gray-800 text-white' : 'bg-white text-black' }}"
              aria-label="Filter companies by category"
            >
              <option value="">All Categories</option>
              @if(!empty($categories))
                @foreach($categories as $category)
                  <option value="{{ $category->slug }}">{{ $category->name }} ({{ $category->count }})</option>
                @endforeach
              @endif
            </select>
          </div>

          {{-- Search Input --}}
          <div>
            <label for="{{ $block_id }}-search" class="block text-sm font-medium mb-2 {{ $textColor }}">
              Search Companies
            </label>
            <input
              type="text"
              id="{{ $block_id }}-search"
              name="search"
              data-search-filter
              placeholder="Search by company name..."
              class="w-full px-3 py-2 border {{ $borderColor }} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent {{ $theme === 'dark' ? 'bg-gray-800 text-white placeholder-gray-400' : 'bg-white text-black placeholder-gray-500' }}"
              aria-label="Search companies by name"
            />
          </div>

          {{-- Submit Button --}}
          <div>
            <button
              type="submit"
              class="w-full px-6 py-2 bg-primary-green-neon text-black font-medium rounded-md hover:bg-primary-green-soft transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:ring-offset-2"
              aria-label="Apply filters"
            >
              Filter
            </button>
          </div>
        </form>

        {{-- Clear Filters --}}
        <div class="mt-4">
          <button
            data-clear-filters
            class="text-sm {{ $textColor }} hover:text-primary-green-neon underline transition-colors duration-200"
            aria-label="Clear all filters"
          >
            Clear All Filters
          </button>
        </div>
      </div>

      {{-- Results Count --}}
      <div class="mb-4">
        <p class="text-sm {{ $textColor }}">
          Showing <span data-results-count>{{ count($companies) }}</span> companies
        </p>
      </div>

      {{-- Companies Table --}}
      <div class="overflow-x-auto" data-table-container>
        <table class="w-full {{ $theme === 'dark' ? 'bg-gray-900' : 'bg-white' }} shadow-lg rounded-lg overflow-hidden">
          <thead class="{{ $theme === 'dark' ? 'bg-gray-800' : 'bg-gray-50' }}">
            <tr>
              <th class="px-6 py-4 text-left text-sm font-semibold {{ $textColor }} uppercase tracking-wider">
                Company Name
              </th>
              <th class="px-6 py-4 text-left text-sm font-semibold {{ $textColor }} uppercase tracking-wider">
                Country
              </th>
              <th class="px-6 py-4 text-left text-sm font-semibold {{ $textColor }} uppercase tracking-wider">
                Country Code
              </th>
              <th class="px-6 py-4 text-left text-sm font-semibold {{ $textColor }} uppercase tracking-wider">
                Currency
              </th>
              <th class="px-6 py-4 text-left text-sm font-semibold {{ $textColor }} uppercase tracking-wider">
                Categories
              </th>
            </tr>
          </thead>
          <tbody data-companies-table class="divide-y {{ $borderColor }}">
            @if(!empty($companies))
              @foreach($companies as $company)
                <tr
                  class="company-row hover:{{ $theme === 'dark' ? 'bg-gray-800' : 'bg-gray-50' }} transition-colors duration-200"
                  data-company-name="{{ strtolower($company['title']) }}"
                  data-country="{{ !empty($company['countries']) ? implode(',', array_map('strtolower', $company['countries'])) : '' }}"
                  data-categories="{{ !empty($company['categories']) ? implode(',', array_map('strtolower', $company['categories'])) : '' }}"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium {{ $textColor }}">
                      {{ $company['title'] }}
                    </div>
                    @if(!empty($company['slug']))
                      <div class="text-xs text-gray-500">
                        {{ $company['slug'] }}
                      </div>
                    @endif
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
