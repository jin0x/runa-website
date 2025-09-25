@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ThemeVariant;

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
    {{-- Filter Section --}}
    <div class="mb-8 p-6 rounded-lg {{ $theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50' }} {{ $borderColor }} border">
      <form id="company-filters" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

        {{-- Country Filter --}}
        <div>
          <label for="country-filter" class="block text-sm font-medium mb-2 {{ $textColor }}">
            Filter by Country
          </label>
          <select
            id="country-filter"
            name="country"
            class="w-full px-3 py-2 border {{ $borderColor }} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent {{ $theme === 'dark' ? 'bg-gray-800 text-white' : 'bg-white text-black' }}"
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
          <label for="category-filter" class="block text-sm font-medium mb-2 {{ $textColor }}">
            Filter by Category
          </label>
          <select
            id="category-filter"
            name="category"
            class="w-full px-3 py-2 border {{ $borderColor }} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent {{ $theme === 'dark' ? 'bg-gray-800 text-white' : 'bg-white text-black' }}"
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
          <label for="search-filter" class="block text-sm font-medium mb-2 {{ $textColor }}">
            Search Companies
          </label>
          <input
            type="text"
            id="search-filter"
            name="search"
            placeholder="Search by company name..."
            class="w-full px-3 py-2 border {{ $borderColor }} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent {{ $theme === 'dark' ? 'bg-gray-800 text-white placeholder-gray-400' : 'bg-white text-black placeholder-gray-500' }}"
          />
        </div>

        {{-- Submit Button --}}
        <div>
          <button
            type="submit"
            class="w-full px-6 py-2 bg-primary-green-neon text-black font-medium rounded-md hover:bg-primary-green-soft transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:ring-offset-2"
          >
            Filter
          </button>
        </div>
      </form>

      {{-- Clear Filters --}}
      <div class="mt-4">
        <button
          id="clear-filters"
          class="text-sm {{ $textColor }} hover:text-primary-green-neon underline transition-colors duration-200"
        >
          Clear All Filters
        </button>
      </div>
    </div>

    {{-- Results Count --}}
    <div class="mb-4">
      <p class="text-sm {{ $textColor }}">
        Showing <span id="results-count">{{ count($companies) }}</span> companies
      </p>
    </div>

    {{-- Companies Table --}}
    <div class="overflow-x-auto">
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
              Categories
            </th>
          </tr>
        </thead>
        <tbody id="companies-table-body" class="divide-y {{ $borderColor }}">
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
    <div id="no-results" class="hidden text-center py-8">
      <p class="text-lg {{ $textColor }} mb-2">No companies found matching your filters.</p>
      <p class="text-sm text-gray-500">Try adjusting your search criteria or clearing all filters.</p>
    </div>
  </x-container>
</x-section>

{{-- JavaScript for filtering --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('company-filters');
    const countryFilter = document.getElementById('country-filter');
    const categoryFilter = document.getElementById('category-filter');
    const searchFilter = document.getElementById('search-filter');
    const clearButton = document.getElementById('clear-filters');
    const tableBody = document.getElementById('companies-table-body');
    const resultsCount = document.getElementById('results-count');
    const noResults = document.getElementById('no-results');
    const companyRows = document.querySelectorAll('.company-row');

    function filterCompanies() {
        const countryValue = countryFilter.value.toLowerCase();
        const categoryValue = categoryFilter.value.toLowerCase();
        const searchValue = searchFilter.value.toLowerCase().trim();

        let visibleCount = 0;

        companyRows.forEach(function(row) {
            const companyName = row.dataset.companyName;
            const companyCountries = row.dataset.country;
            const companyCategories = row.dataset.categories;

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

            if (showRow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update results count
        resultsCount.textContent = visibleCount;

        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            tableBody.parentElement.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            tableBody.parentElement.classList.remove('hidden');
        }
    }

    // Filter on form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        filterCompanies();
    });

    // Filter on input change
    countryFilter.addEventListener('change', filterCompanies);
    categoryFilter.addEventListener('change', filterCompanies);
    searchFilter.addEventListener('input', filterCompanies);

    // Clear filters
    clearButton.addEventListener('click', function() {
        countryFilter.value = '';
        categoryFilter.value = '';
        searchFilter.value = '';
        filterCompanies();
    });
});
</script>