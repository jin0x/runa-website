@php
  use App\Enums\ContainerSize;
  use App\Enums\SectionSize;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Show navigation when show_navigation is true, and there is more than one testimonial
  $showNavigation = $show_navigation && (count($testimonials) > 1);
  $singleDisplay = $display_layout === 'single';
@endphp

@if($testimonials && count($testimonials) > 0)
  <x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ !$singleDisplay  ? 'testimonials-slider-block' : 'testimonials-slider-block-featured' }} {{ $block->classes ?? '' }}">
    <x-container :size="ContainerSize::XLARGE">

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

      @if($display_layout === 'single')
        {{-- Single Testimonial Display with Slider --}}
        <x-slider
          :navigation="$show_navigation"
          :pagination="false"
          :loop="true"
          :autoplayDelay="$autoplay_delay * 1000"
          :slidesPerView="1"
          :mobileSlidesPerView="1"
          :tabletSlidesPerView="1"
          :desktopSlidesPerView="1"
          :spaceBetween="256"
          :navigationPosition="'bottom-right'"
          :slideCount="count($testimonials)"
        >
          @foreach($testimonials as $testimonial)
            <div class="swiper-slide">
              <x-testimonial-card
                :post="$testimonial->ID"
                :featured="true"
                :cardColor="$card_color"
                :showLogo="$show_company_logos"
                :showRating="$show_ratings"
              />
            </div>
          @endforeach
        </x-slider>
      @else
        {{-- Slider Display --}}
        <x-slider
          :navigation="$show_navigation"
          :pagination="false"
          :loop="true"
          :autoplayDelay="$autoplay_delay * 1000"
          :slidesPerView="1"
          :mobileSlidesPerView="1"
          :tabletSlidesPerView="2"
          :desktopSlidesPerView="2"
          :spaceBetween="24"
          :navigationPosition="'bottom-right'"
          :slideCount="count($testimonials)"
        >
          @foreach($testimonials as $testimonial)
            <div class="swiper-slide">
              <x-testimonial-card
                :post="$testimonial->ID"
                :featured="false"
                :cardColor="$card_color"
                :showLogo="$show_company_logos"
                :showRating="$show_ratings"
              />
            </div>
          @endforeach
        </x-slider>
      @endif
    </x-container>
  </x-section>
@else
  {{-- No testimonials fallback --}}
  <x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes ?? '' }}">
    <x-container :size="$containerSize">
      <div class="text-center">
        <x-text
          class="text-lg"
        >
          No testimonials available. Create some testimonials to display them here.
        </x-text>
      </div>
    </x-container>
  </x-section>
@endif
