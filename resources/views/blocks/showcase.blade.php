@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
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

  // Map accent colors to CSS classes
  $accentClasses = match ($accent_color) {
      'green-soft' => 'text-primary-green-soft',
      'yellow' => 'text-primary-yellow',
      'pink' => 'text-secondary-pink',
      'purple' => 'text-secondary-purple',
      'cyan' => 'text-secondary-cyan',
      default => 'text-primary-green-neon',
  };

  // Button variant based on accent color
  $buttonVariant = match ($accent_color) {
      'green-soft' => ButtonVariant::PRIMARY,
      default => ButtonVariant::PRIMARY,
  };

  // Handle media URL
  $media_url = '';
  if ($media_type === 'video' && !empty($video) && is_array($video)) {
      $media_url = $video['url'] ?? '';
  } elseif ($media_type === 'image' && !empty($image)) {
      if (is_array($image)) {
          $media_url = $image['url'] ?? '';
      } else {
          $media_url = $image;
      }
  } elseif ($media_type === 'lottie' && !empty($lottie) && is_array($lottie)) {
      $media_url = $lottie['url'] ?? '';
  }

  // Generate unique ID for marquee
  $marqueeId = 'showcase-marquee-' . uniqid();
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">

  @if($section_eyebrow || $section_title || $section_description)
    @php
      // Process heading with accent highlights if section_title exists
      $processedHeading = $section_title ? preg_replace('/\b(Fund|Pay|Own)\b/', '<span class="' .  $accentClasses . '">$1</span>', $section_title) : null;
    @endphp

    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$processedHeading"
      :subtitle="$section_description"
      :variant="$themeVariant"
      classes="mb-12"
    />
  @endif

  <x-container>
    <div class="text-center">

    {{-- Statistics Cards --}}
    @if(!empty($statistics_cards))
      <div class="grid grid-cols-2 md:grid-cols-4 p-6 mb-12">
        @foreach($statistics_cards as $card)
          <div class="text-center">
            @php
              $card_image = $card['icon'] ?? null;
              $card_link = $card['link'] ?? null;
              $alt_text = $card['alt_text'] ?? ($card_image['alt'] ?? 'Client icon');
              $card_url = $card_image['url'] ?? '';
            @endphp

            {{-- Icon --}}
            @if(!empty($card['icon']))
              <div class="mb-4 flex justify-center">
                <img
                  src="{{ $card_url }}"
                  alt="{{ $alt_text ?? 'Statistic icon' }}"
                  class="w-12 h-12 object-contain"
                />
              </div>
            @endif

            {{-- Statistic --}}
            @if(!empty($card['statistic']))
              <x-heading
                :as="HeadingTag::H3"
                :size="HeadingSize::H2"
                class="mb-2 text-primary-green-soft"
              >
                {{ $card['statistic'] }}
              </x-heading>
            @endif

            {{-- Description --}}
            @if(!empty($card['description']))
              <x-text
                :as="TextTag::P"
                :size="TextSize::SMALL"
                class="text-white"
              >
                {{ $card['description'] }}
              </x-text>
            @endif
          </div>
        @endforeach
      </div>
    @endif

        {{-- Media Section --}}
    @if($media_type === 'logo-marquee' && !empty($marquee_logos))
      {{-- 3-Lane Logo Marquee --}}
      <div class="mb-12 py-8 overflow-hidden">
        {{-- Lane 1: Left to Right --}}
        <div class="marquee__lane mb-3.5 overflow-hidden">
          <div class="marquee__inner" id="{{ $marqueeId }}-lane1">
            @for ($i = 0; $i < 2; $i++)
              <div class="marquee__part flex items-center gap-7">
                @foreach($marquee_logos as $logo)
                  @php
                    $logo_image = $logo['logo'] ?? null;
                    $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Logo');
                    $logo_url = $logo_image['url'] ?? '';
                  @endphp
                  
                  @if(!empty($logo_url))
                    <div class="flex items-center justify-center">
                      <img 
                        src="{{ $logo_url }}" 
                        alt="{{ $alt_text }}" 
                        class="h-12 w-auto max-w-full object-contain opacity-90 hover:opacity-100 transition-opacity"
                      >
                    </div>
                  @endif
                @endforeach
              </div>
            @endfor
          </div>
        </div>

        {{-- Lane 2: Right to Left --}}
        <div class="marquee__lane mb-3.5 overflow-hidden">
          <div class="marquee__inner" id="{{ $marqueeId }}-lane2">
            @for ($i = 0; $i < 2; $i++)
              <div class="marquee__part flex items-center gap-7 mr-3.5">
                @foreach($marquee_logos as $logo)
                  @php
                    $logo_image = $logo['logo'] ?? null;
                    $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Logo');
                    $logo_url = $logo_image['url'] ?? '';
                  @endphp
                  
                  @if(!empty($logo_url))
                    <div class="flex items-center justify-center">
                      <img 
                        src="{{ $logo_url }}" 
                        alt="{{ $alt_text }}" 
                        class="h-12 w-auto max-w-full object-contain opacity-90 hover:opacity-100 transition-opacity"
                      >
                    </div>
                  @endif
                @endforeach
              </div>
            @endfor
          </div>
        </div>

        {{-- Lane 3: Left to Right --}}
        <div class="marquee__lane overflow-hidden">
          <div class="marquee__inner" id="{{ $marqueeId }}-lane3">
            @for ($i = 0; $i < 2; $i++)
              <div class="marquee__part flex items-center gap-7">
                @foreach($marquee_logos as $logo)
                  @php
                    $logo_image = $logo['logo'] ?? null;
                    $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Logo');
                    $logo_url = $logo_image['url'] ?? '';
                  @endphp
                  
                  @if(!empty($logo_url))
                    <div class="flex items-center justify-center">
                      <img 
                        src="{{ $logo_url }}" 
                        alt="{{ $alt_text }}" 
                        class="h-12 w-auto max-w-full object-contain opacity-90 hover:opacity-100 transition-opacity"
                      >
                    </div>
                  @endif
                @endforeach
              </div>
            @endfor
          </div>
        </div>
      </div>

      {{-- GSAP Animation Script --}}
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          function initShowcaseMarquee() {
            if (window.gsap) {
              // Lane 1: Left to Right
              const setupLane1 = () => {
                window.gsap.set("#{{ $marqueeId }}-lane1", { xPercent: 0 });
                window.gsap.timeline({
                  defaults: { ease: 'none', repeat: -1 }
                })
                .to("#{{ $marqueeId }}-lane1", {
                  xPercent: -50,
                  duration: 20,
                })
                .set("#{{ $marqueeId }}-lane1", { x: 0 });
              };
            
              // Lane 2: Right to Left (reversed)
              const setupLane2 = () => {
                window.gsap.set("#{{ $marqueeId }}-lane2", { xPercent: -50 });
                window.gsap.timeline({
                  defaults: { ease: 'none', repeat: -1 }
                })
                .to("#{{ $marqueeId }}-lane2", {
                  xPercent: 0,
                  duration: 22,
                })
                .set("#{{ $marqueeId }}-lane2", { x: 0 });
              };
            
              // Lane 3: Left to Right
              const setupLane3 = () => {
                window.gsap.set("#{{ $marqueeId }}-lane3", { xPercent: 0 });
                window.gsap.timeline({
                  defaults: { ease: 'none', repeat: -1 }
                })
                .to("#{{ $marqueeId }}-lane3", {
                  xPercent: -50,
                  duration: 20,
                })
                .set("#{{ $marqueeId }}-lane3", { x: 0 });
              };
            
              // Initialize all lanes
              setupLane1();
              setupLane2();
              setupLane3();
            
            } else {
              setTimeout(initShowcaseMarquee, 100);
            }
          }

          setTimeout(initShowcaseMarquee, 100);
        });
      </script>
    @endif

    {{-- Media Section --}}
    @if(!empty($media_url))
      <div class="mb-12 flex justify-center">
        <div class="w-full ">
          <x-media
            :mediaType="$media_type"
            :mediaUrl="$media_url"
            :altText="$heading ?? 'Showcase media'"
            classes="w-full h-auto min-h-6xl object-contain"
          />
        </div>
      </div>
    @endif

      {{-- Call to Action --}}
      @if(!empty($cta) && !empty($cta['url']) && !empty($cta['title']))
        <div class="flex justify-center">
          <x-button
            :variant="$buttonVariant"
            :href="$cta['url']"
            target="{{ $cta['target'] ?? '_self' }}"
          >
            {{ $cta['title'] }}
          </x-button>
        </div>
      @endif
    </div>
  </x-container>
</x-section>
