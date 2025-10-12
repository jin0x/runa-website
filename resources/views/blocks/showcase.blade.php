@php
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
  use App\Enums\ButtonSize;
  use App\Enums\ThemeVariant;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

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

    // Set grid column values
  $gridColumns = match($columns) {
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
    default => '4', // Default to 3 columns if invalid value
  };
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  <x-container>

    @if($section_eyebrow || $section_title || $section_description)
      <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_title"
        :subtitle="$section_description"
        :variant="$sectionHeadingVariant"
        isShowcase="true"
        classes="mb-12"
      />
    @endif

    <div class="text-center">

    {{-- Statistics Cards --}}
    @if(!empty($statistics_cards))
      <x-grid :columns="$gridColumns" class="p-6 mb-12">
        @foreach($statistics_cards as $card)
          <div class="text-center p-6 max-w-52 justify-self-center">
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
                :size="HeadingSize::HERO_MEDIUM"
                :color="TextColor::GREEN_SOFT"
                class="mb-2"
              >
                {{ $card['statistic'] }}
              </x-heading>
            @endif

            {{-- Description --}}
            @if(!empty($card['description']))
              <x-text
                :as="TextTag::P"
                :size="TextSize::MEDIUM"
                :color="TextColor::DARK"
              >
                {{ $card['description'] }}
              </x-text>
            @endif
          </div>
        @endforeach
      </x-grid>
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

      {{-- Modern GSAP Animation Script --}}
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const marqueeId = '{{ $marqueeId }}';

          // Modern GSAP initialization using centralized manager
          const initShowcaseMarquee = () => {
            const lanes = [
              { selector: `#${marqueeId}-lane1`, direction: 1, duration: 20 },
              { selector: `#${marqueeId}-lane2`, direction: -1, duration: 22 },
              { selector: `#${marqueeId}-lane3`, direction: 1, duration: 20 }
            ];

            const animations = [];

            lanes.forEach(({ selector, direction, duration }) => {
              const element = document.querySelector(selector);
              if (!element) return;

              // Add CSS optimization
              element.style.willChange = 'transform';

              // Modern GSAP animation - no timeline needed for simple infinite loops
              const animation = window.gsap.to(selector, {
                xPercent: direction === 1 ? -50 : 0,
                duration: duration,
                ease: "none",
                repeat: -1,
                force3D: true, // GPU acceleration
                // Set initial position based on direction
                ...(direction === -1 && {
                  immediateRender: true,
                  startAt: { xPercent: -50 }
                })
              });

              animations.push(animation);
            });

            // Return cleanup function for memory management
            return () => {
              animations.forEach(animation => animation?.kill());
              lanes.forEach(({ selector }) => {
                const element = document.querySelector(selector);
                if (element) {
                  element.style.willChange = 'auto';
                }
              });
            };
          };

          // Register with GSAPManager for proper lifecycle management
          if (window.GSAPManager) {
            window.GSAPManager.register(
              `showcase-marquee-${marqueeId}`,
              initShowcaseMarquee
            );
          } else {
            // Fallback for legacy initialization
            console.warn('GSAPManager not available, using legacy initialization');
            if (window.gsap) {
              initShowcaseMarquee();
            } else {
              setTimeout(() => {
                if (window.gsap) initShowcaseMarquee();
              }, 100);
            }
          }
        });
      </script>
    @endif

    {{-- Grid Layout Section --}}
    @if($media_type === 'grid' && !empty($grid_items) && count($grid_items) === 4)
      <div class="mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
          @foreach($grid_items as $index => $item)
            @php
              $item_image = $item['grid_image'] ?? null;
              $item_title = $item['grid_title'] ?? '';
              $item_description = $item['grid_description'] ?? '';
              $image_url = is_array($item_image) ? ($item_image['url'] ?? '') : '';
              $image_alt = is_array($item_image) ? ($item_image['alt'] ?? $item_title) : $item_title;

              // Determine if this is left column (0, 1) or right column (2, 3)
              $isLeftColumn = $index < 2;
            @endphp

            <div class="showcase-grid-item group relative overflow-hidden rounded-2xl bg-gray-900/80 p-6 transition-all duration-300 hover:bg-gray-900">
              @if($isLeftColumn)
                {{-- Left Column: Text then Image (side by side on desktop, stacked on mobile) --}}
                <div class="flex flex-col md:flex-row gap-4 md:gap-6 items-center">
                  <div class="showcase-grid-text flex-1">
                    @if(!empty($item_title))
                      <x-heading
                        :as="HeadingTag::H3"
                        :size="HeadingSize::H3"
                        :color="TextColor::GREEN_SOFT"
                        class="mb-3"
                      >
                        {{ $item_title }}
                      </x-heading>
                    @endif

                    @if(!empty($item_description))
                      <x-text
                        :as="TextTag::P"
                        :size="TextSize::MEDIUM"
                        :color="TextColor::LIGHT"
                      >
                        {{ $item_description }}
                      </x-text>
                    @endif
                  </div>

                  @if(!empty($image_url))
                    <div class="showcase-grid-image flex-1">
                      <img
                        src="{{ $image_url }}"
                        alt="{{ $image_alt }}"
                        class="w-full h-auto rounded-lg object-cover"
                      />
                    </div>
                  @endif
                </div>
              @else
                {{-- Right Column: Image then Text (side by side on desktop, stacked on mobile) --}}
                <div class="flex flex-col md:flex-row gap-4 md:gap-6 items-center">
                  @if(!empty($image_url))
                    <div class="showcase-grid-image flex-1">
                      <img
                        src="{{ $image_url }}"
                        alt="{{ $image_alt }}"
                        class="w-full h-auto rounded-lg object-cover"
                      />
                    </div>
                  @endif

                  <div class="showcase-grid-text flex-1">
                    @if(!empty($item_title))
                      <x-heading
                        :as="HeadingTag::H3"
                        :size="HeadingSize::H3"
                        :color="TextColor::GREEN_SOFT"
                        class="mb-3"
                      >
                        {{ $item_title }}
                      </x-heading>
                    @endif

                    @if(!empty($item_description))
                      <x-text
                        :as="TextTag::P"
                        :size="TextSize::MEDIUM"
                        :color="TextColor::LIGHT"
                      >
                        {{ $item_description }}
                      </x-text>
                    @endif
                  </div>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
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
            :size="ButtonSize::LARGE"
            target="{{ $cta['target'] ?? '_self' }}"
          >
            {{ $cta['title'] }}
          </x-button>
        </div>
      @endif
    </div>
  </x-container>
</x-section>
