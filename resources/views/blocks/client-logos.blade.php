@php
  /**
   * Client Logos Block
   */
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Background color handled by section component via $themeVariant

  // Marquee fade gradient classes based on theme
  $fadeLeftClass = match ($themeVariant) {
      ThemeVariant::DARK => 'marquee-fade-left-dark',
      default => 'marquee-fade-left',
  };

  $fadeRightClass = match ($themeVariant) {
      ThemeVariant::DARK => 'marquee-fade-right-dark',
      default => 'marquee-fade-right',
  };

  // Section heading color is handled by section-heading component itself via theme variant

  // Configure logo styles - optimized for both square and wide logos
  $logoContainerClasses = 'flex items-center justify-center w-full min-h-[100px] p-4';
  $logoClasses = 'h-12 w-auto max-w-full object-contain transition-opacity hover:opacity-80';

  // Extract just the number from the grid_columns value
  $columnsNumber = is_string($grid_columns) ? preg_replace('/[^0-9]/', '', $grid_columns) : '4';

  // Ensure we have valid values for all grid properties
  $columnsNumber = !empty($columnsNumber) ? $columnsNumber : '4';
  $gridGap = !empty($grid_gap) ? $grid_gap : 'lg';
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

  {{-- Logos Display --}}
  @if(!empty($logos))
  @if($layout_type === 'grid')
    <x-grid
      columns="{{ $columnsNumber }}"
      gapsize="{{ $gridGap }}"
      rowgapsize="lg"
      colgapsize="lg"
      classes="items-center justify-center"
    >
      @foreach($logos as $logo)
        <div class="{{ $logoContainerClasses }}">
          @php
            $logo_image = $logo['logo'] ?? null;
            $logo_link = $logo['link'] ?? null;
            $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Client logo');
            $logo_url = $logo_image['url'] ?? '';
          @endphp

          @if(!empty($logo_url))
            @if(!empty($logo_link) && !empty($logo_link['url']))
              <a href="{{ $logo_link['url'] }}"
                target="{{ $logo_link['target'] ?? '_self' }}"
                class="flex items-center justify-center"
              >
                <img src="{{ $logo_url }}" alt="{{ $alt_text }}" class="{{ $logoClasses }}">
              </a>
            @else
              <img src="{{ $logo_url }}" alt="{{ $alt_text }}" class="{{ $logoClasses }}">
            @endif
          @endif
        </div>
      @endforeach
    </x-grid>

  @elseif($layout_type === 'marquee')
    {{-- Marquee Layout --}}
    @php $marqueeId = 'marquee-' . uniqid(); @endphp

    <div class="py-6 overflow-hidden">
      {{-- Fade gradients --}}
      <div class="absolute left-0 top-0 bottom-0 w-16 {{ $fadeLeftClass }} z-10 pointer-events-none"></div>
      <div class="absolute right-0 top-0 bottom-0 w-16 {{ $fadeLeftClass }} z-10 pointer-events-none"></div>

      <div class="marquee__inner" id="{{ $marqueeId }}" aria-hidden="true">
        {{-- Create multiple parts for smooth scrolling --}}
        @for ($i = 0; $i < 6; $i++)
          <div class="marquee__part flex items-center gap-6 flex-shrink-0 pr-6">
            @foreach($logos as $logo)
              @php
                $logo_image = $logo['logo'] ?? null;
                $logo_link = $logo['link'] ?? null;
                $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Client   logo');
                $logo_url = $logo_image['url'] ?? '';
              @endphp

              @if(!empty($logo_url))
                <div class="flex items-center justify-center px-3">
                  @if(!empty($logo_link) && !empty($logo_link['url']))
                    <a href="{{ $logo_link['url'] }}"
                      target="{{ $logo_link['target'] ?? '_self' }}"
                      class="flex items-center justify-center"
                    >
                      <img src="{{ $logo_url }}" alt="{{ $alt_text }}"  class="h-12 w-auto max-w-full object-contain">
                    </a>
                  @else
                    <img src="{{ $logo_url }}" alt="{{ $alt_text }}"  class="h-12 w-auto max-w-full object-contain">
                  @endif
                </div>
              @endif
            @endforeach
          </div>
        @endfor
      </div>
    </div>

  {{-- Modern GSAP Animation Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const marqueeId = '{{ $marqueeId }}';

      // Modern GSAP initialization
      const initMarquee = () => {
        const marqueeInner = document.getElementById(marqueeId);
        if (!marqueeInner) return;

        const parts = marqueeInner.querySelectorAll('.marquee__part');
        if (parts.length === 0) return;

        // Add CSS optimization
        marqueeInner.style.willChange = 'transform';
        parts.forEach(part => {
          part.style.willChange = 'transform';
        });

        // Modern GSAP animation with better performance
        const animation = window.gsap.to(parts, {
          xPercent: -100,
          duration: 20,
          ease: "none",
          repeat: -1,
          force3D: true
        });

        // Set initial position for smooth infinite scroll
        window.gsap.set(marqueeInner, { xPercent: -50 });

        console.log('✅ Client logos marquee animation started');

        // Return cleanup function
        return () => {
          animation?.kill();
          marqueeInner.style.willChange = 'auto';
          parts.forEach(part => {
            part.style.willChange = 'auto';
          });
        };
      };

      // Register with GSAPManager
      if (window.GSAPManager) {
        window.GSAPManager.register(
          `client-logos-marquee-${marqueeId}`,
          initMarquee
        );
      } else {
        // Fallback for legacy initialization
        console.warn('GSAPManager not available, using legacy initialization');
        if (window.gsap) {
          initMarquee();
        } else {
          setTimeout(() => {
            if (window.gsap) initMarquee();
          }, 100);
        }
      }
    });
  </script>

  @else
    {{-- Original Slider Layout --}}
    <x-slider
      :navigation="$slider_navigation"
      :pagination="$slider_pagination"
      :loop="$slider_loop"
      :autoplayDelay="$slider_autoplay_delay"
      :spaceBetween="$slider_space_between"
      :mobileSlidesPerView="$slider_mobile_slides"
      :tabletSlidesPerView="$slider_tablet_slides"
      :desktopSlidesPerView="$slider_desktop_slides"
      classes="client-logos-slider"
    >
      @foreach($logos as $logo)
        <div class="swiper-slide {{ $logoContainerClasses }}">
          @php
            $logo_image = $logo['logo'] ?? null;
            $logo_link = $logo['link'] ?? null;
            $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Client logo');
            $logo_url = $logo_image['url'] ?? '';
          @endphp

          @if(!empty($logo_url))
            @if(!empty($logo_link) && !empty($logo_link['url']))
              <a href="{{ $logo_link['url'] }}"
                 target="{{ $logo_link['target'] ?? '_self' }}"
                 class="flex items-center justify-center"
              >
                <img src="{{ $logo_url }}" alt="{{ $alt_text }}" class="{{ $logoClasses }}">
              </a>
            @else
              <img src="{{ $logo_url }}" alt="{{ $alt_text }}" class="{{ $logoClasses }}">
            @endif
          @endif
        </div>
      @endforeach
    </x-slider>
  @endif
@else
  <div class="text-center text-gray-500 py-8">No logos have been added yet.</div>
@endif
</x-section>
