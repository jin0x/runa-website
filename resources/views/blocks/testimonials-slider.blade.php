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
@endphp

@if($testimonials && count($testimonials) > 0)
  <x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="testimonials-slider-block-featured {{ $block->classes ?? '' }}">
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

      <x-slider
        :navigation="$showNavigation"
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
            @if($display_layout === 'logo_featured')
              <x-testimonial-card-right-logo
              :post="$testimonial->ID"
              :cardColor="$card_color" />
            @else
              <x-testimonial-card-right-quote
              :post="$testimonial->ID"
              :cardColor="$card_color" />
            @endif
          </div>
        @endforeach
      </x-slider>

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
