@php
  /**
   * Client Logos Block
   */
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;

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
      'dark' => 'bg-primary-dark',
      default => 'bg-white',
  };

  // Configure logo styles with reduced dimensions for a more compact display
  $logoContainerClasses = 'flex items-center justify-center w-full max-h-24 p-3';
  $logoClasses = 'max-h-12 max-w-[140px] w-auto object-contain transition-opacity hover:opacity-80';

  // Extract just the number from the grid_columns value
  $columnsNumber = is_string($grid_columns) ? preg_replace('/[^0-9]/', '', $grid_columns) : '4';

  // Ensure we have valid values for all grid properties
  $columnsNumber = !empty($columnsNumber) ? $columnsNumber : '4';
  $gridGap = !empty($grid_gap) ? $grid_gap : 'lg';
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">
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
  
    <div class="py-6 overflow-hidden bg-primary-yellow">
      {{-- Fade gradients --}}
      <div class="absolute left-0 top-0 bottom-0 w-16 marquee-fade-left z-10    pointer-events-none"></div>
      <div class="absolute right-0 top-0 bottom-0 w-16 marquee-fade-right z-10    pointer-events-none"></div>

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
                      <img src="{{ $logo_url }}" alt="{{ $alt_text }}"  class="max-h-12 max-w-[140px] w-auto object-contain">
                    </a>
                  @else
                    <img src="{{ $logo_url }}" alt="{{ $alt_text }}"  class="max-h-12 max-w-[140px] w-auto object-contain">
                  @endif
                </div>
              @endif
            @endforeach
          </div>
        @endfor
      </div>
    </div>

  {{-- GSAP Animation Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const marqueeInner = document.getElementById('{{ $marqueeId }}');

      if (marqueeInner) {
        const gsapInstance = window.gsap || gsap;

        if (gsapInstance) {
          // Wait a moment for images to load
          setTimeout(function() {
            // Create the infinite animation 
            let tween = gsapInstance.to("#{{ $marqueeId }} .marquee__part", {
              xPercent: -100, 
              repeat: -1, 
              duration: 20,
              ease: "linear"
            }).totalProgress(0.5);

            // Center the marquee
            gsapInstance.set("#{{ $marqueeId }}", {xPercent: -50});

            console.log('CodePen-style marquee animation started');
          }, 500);
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
