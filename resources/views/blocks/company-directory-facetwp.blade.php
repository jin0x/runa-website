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
    {{-- Company Directory Container with unique block ID --}}
    <div
      id="{{ $block_id }}"
      data-company-directory-facetwp
      class="company-directory-facetwp-block"
      data-theme="{{ $theme }}"
    >
      {{-- Filter Section --}}
      <div class="mb-8 p-6 rounded-lg {{ $theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50' }} {{ $borderColor }} border">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

          {{-- Country Filter --}}
          <div>
            <label for="{{ $block_id }}-country" class="block text-sm font-medium mb-2 {{ $textColor }}">
              Filter by Country
            </label>
            <div class="facetwp-facet-container">
              {!! facetwp_display('facet', 'company_country') !!}
            </div>
          </div>

          {{-- Category Filter --}}
          <div>
            <label for="{{ $block_id }}-category" class="block text-sm font-medium mb-2 {{ $textColor }}">
              Filter by Category
            </label>
            <div class="facetwp-facet-container">
              {!! facetwp_display('facet', 'company_category') !!}
            </div>
          </div>

          {{-- Search Input --}}
          <div>
            <label for="{{ $block_id }}-search" class="block text-sm font-medium mb-2 {{ $textColor }}">
              Search Companies
            </label>
            <div class="facetwp-facet-container">
              {!! facetwp_display('facet', 'company_search') !!}
            </div>
          </div>

          {{-- Clear Filters Button --}}
          <div>
            <button
              type="button"
              onclick="FWP.reset()"
              class="w-full px-6 py-2 bg-primary-green-neon text-black font-medium rounded-md hover:bg-primary-green-soft transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:ring-offset-2"
              aria-label="Clear all filters"
            >
              Clear Filters
            </button>
          </div>
        </form>
      </div>

      {{-- Results Count --}}
      <div class="mb-4">
        <p class="text-sm {{ $textColor }}">
          {!! facetwp_display('counts') !!}
        </p>
      </div>

      {{-- Companies Table --}}
      <div class="overflow-x-auto">
        <div class="facetwp-template" data-name="company_directory">
          {{-- Companies will be loaded here via FacetWP --}}
          @if(function_exists('facetwp_display'))
            {!! facetwp_display('template', 'company_directory') !!}
          @else
            <div class="text-center py-8">
              <p class="text-lg {{ $textColor }} mb-2">FacetWP is not active.</p>
              <p class="text-sm text-gray-500">Please activate FacetWP plugin to use the filtering functionality.</p>
            </div>
          @endif
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
    const bgColor = isDark ? 'bg-gray-800' : 'bg-white';
    const textColor = isDark ? 'text-white' : 'text-black';
    const placeholderColor = isDark ? 'placeholder-gray-400' : 'placeholder-gray-500';

    // Style dropdowns
    const selects = this.block.querySelectorAll('.facetwp-dropdown select');
    selects.forEach(select => {
        select.className = `w-full px-3 py-2 border ${borderColor} ${bgColor} ${textColor} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent`;
    });

    // Style search input
    const searchInputs = this.block.querySelectorAll('.facetwp-search input');
    searchInputs.forEach(input => {
        input.className = `w-full px-3 py-2 border ${borderColor} ${bgColor} ${textColor} ${placeholderColor} rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green-neon focus:border-transparent`;
    });
};

CompanyDirectoryFacetWP.prototype.setupFacetWPEvents = function() {
    // Add loading state when filtering
    document.addEventListener('facetwp-refresh', function() {
        if (FWP.loaded) {
            this.addLoadingState();
        }
    }.bind(this));

    // Remove loading state when done
    document.addEventListener('facetwp-loaded', function() {
        this.removeLoadingState();
        this.styleFacetWPElements(); // Re-apply styles after AJAX
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
</style>