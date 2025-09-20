@php
  use App\Enums\ContainerSize;
  use App\Enums\SectionSize;
  use App\Enums\ThemeVariant;

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

  // Convert theme string to ThemeVariant enum
  $themeVariant = match ($theme) {
      'dark' => ThemeVariant::DARK,
      'light' => ThemeVariant::LIGHT,
      'accent' => ThemeVariant::ACCENT,
      default => ThemeVariant::ACCENT,
  };

  // Set background color based on theme
  $bgColor = match ($theme) {
      'dark' => 'bg-primary-dark',
      'light' => 'bg-white',
      'accent' => 'bg-primary-green-neon',
      default => 'bg-primary-green-neon',
  };

  // Set container size based on section size
  $containerSize = match ($section_size) {
      'xl' => ContainerSize::XLARGE,
      'lg' => ContainerSize::LARGE,
      default => ContainerSize::MEDIUM,
  };

  // Set text color based on theme
  $textColor = match ($theme) {
      'light' => 'text-primary-dark',
      default => 'text-white',
  };
@endphp

@if($testimonials && count($testimonials) > 0)
  <x-section :size="$sectionSizeValue" classes="testimonials-slider-block {{ $bgColor }} {{ $block->classes ?? '' }}">
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
  <x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes ?? '' }}">
    <x-container :size="$containerSize">
      <div class="text-center">
        <p class="text-lg {{ $textColor }}">
          No testimonials available. Create some testimonials to display them here.
        </p>
      </div>
    </x-container>
  </x-section>
@endif