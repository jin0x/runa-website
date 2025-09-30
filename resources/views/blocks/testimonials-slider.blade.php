@php
  use App\Enums\ContainerSize;
  use App\Enums\SectionSize;
  use App\Enums\ThemeVariant;
  use App\Enums\TextColor;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = match ($section_size) {
      'none' => SectionSize::NONE,
      'xs' => SectionSize::XSMALL,
      'sm' => SectionSize::SMALL,
      'md' => SectionSize::MEDIUM,
      'lg' => SectionSize::LARGE,
      'xl' => SectionSize::XLARGE,
      default => SectionSize::LARGE,
  };

  // Set theme variant based on theme
  $themeVariant = match ($theme) {
      'dark' => ThemeVariant::DARK,
      'light' => ThemeVariant::LIGHT,
      'green' => ThemeVariant::GREEN,
      default => ThemeVariant::GREEN,
  };

  // Set container size based on section size
  $containerSize = match ($section_size) {
      'xl' => ContainerSize::XLARGE,
      'lg' => ContainerSize::LARGE,
      default => ContainerSize::MEDIUM,
  };

  // Set text color based on theme
  $textColor = match ($theme) {
      'light' => TextColor::DARK,
      default => TextColor::LIGHT,
  };
@endphp

@if($testimonials && count($testimonials) > 0)
  <x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="testimonials-slider-block {{ $block->classes ?? '' }}">
    <x-container :size="$containerSize">

      <x-slider
        :navigation="$show_navigation"
        :pagination="false"
        :loop="true"
        :autoplayDelay="$autoplay_delay * 1000"
        :slidesPerView="1"
        :mobileSlidesPerView="1"
        :tabletSlidesPerView="2"
        :desktopSlidesPerView="3"
        :spaceBetween="24"
        :navigationPosition="'bottom-right'"
      >
        @foreach($testimonials as $testimonial)
          <div class="swiper-slide">
            <x-testimonial-card
              :post="$testimonial->ID"
              :featured="false"
            />
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
          :color="$textColor"
          class="text-lg"
        >
          No testimonials available. Create some testimonials to display them here.
        </x-text>
      </div>
    </x-container>
  </x-section>
@endif