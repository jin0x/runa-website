@php
  use App\Enums\ContainerSize;
  use App\Enums\SectionSize;
  use App\Enums\ThemeVariant;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert theme string to ThemeVariant enum
  $themeVariant = match ($theme) {
      'dark' => ThemeVariant::DARK,
      'green' => ThemeVariant::DARK,
      default => ThemeVariant::LIGHT,
  };

  // Set container size based on section size
  $containerSize = match ($section_size) {
      'xl' => ContainerSize::XLARGE,
      'lg' => ContainerSize::LARGE,
      default => ContainerSize::MEDIUM,
  };

  // Set text color based on theme
  $textColor = match ($themeVariant) {
      ThemeVariant::LIGHT => TextColor::DARK,
      default => TextColor::LIGHT,
  };
  // Set container size based on layout
  $containerSize = $display_layout === 'single' ? ContainerSize::MEDIUM : ContainerSize::XLARGE;
@endphp

@if($testimonials && count($testimonials) > 0)
  <x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="testimonials-slider-block {{ $block->classes ?? '' }}">
    <x-container :size="$containerSize">

    {{-- Section Heading --}}
    @if($section_eyebrow || $section_title || $section_description)
    <div class="justify-self-center mb-12 text-center">
      <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$themeVariant"
      classes="mb-12"
      />
    </div>
    @endif

    <x-container :size="$containerSize">
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
            :spaceBetween="24"
            :navigationPosition="'bottom-right'"
          >
            @foreach($testimonials as $testimonial)
              <div class="swiper-slide">
                <x-testimonial-card
                  :post="$testimonial->ID"
                  :featured="false"
                  :cardColor="$card_background_color"
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
        >
          @foreach($testimonials as $testimonial)
            <div class="swiper-slide">
              <x-testimonial-card
                :post="$testimonial->ID"
                :featured="false"
                :cardColor="$card_background_color"
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
          :color="$textColor"
          class="text-lg"
        >
          No testimonials available. Create some testimonials to display them here.
        </x-text>
      </div>
    </x-container>
  </x-section>
@endif