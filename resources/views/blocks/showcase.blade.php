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
  use App\Enums\ArchPosition;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  $archPositionValue = EnumHelper::getArchPosition($arch_position ?? 'none');

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

  // Split logos into 3 groups for visual variety across lanes
  if (!empty($marquee_logos)) {
      $logoChunks = array_chunk($marquee_logos, ceil(count($marquee_logos) / 3));
      $lane1Logos = $logoChunks[0] ?? [];
      $lane2Logos = $logoChunks[1] ?? $logoChunks[0] ?? [];
      $lane3Logos = $logoChunks[2] ?? $logoChunks[0] ?? [];
  } else {
      $lane1Logos = $lane2Logos = $lane3Logos = [];
  }

    // Set grid column values
  $gridColumns = match($columns) {
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
    default => '4', // Default to 3 columns if invalid value
  };

  // Marquee fade gradient classes based on theme
  $fadeLeftClass = match ($themeVariant) {
      ThemeVariant::DARK => 'marquee-fade-left-dark',
      ThemeVariant::CYAN => 'marquee-fade-left-cyan',
      ThemeVariant::YELLOW => 'marquee-fade-left-yellow',
      ThemeVariant::LIGHT => 'marquee-fade-left-light',
      default => 'marquee-fade-left',
  };

  $fadeRightClass = match ($themeVariant) {
      ThemeVariant::DARK => 'marquee-fade-right-dark',
      ThemeVariant::CYAN => 'marquee-fade-right-cyan',
      ThemeVariant::YELLOW => 'marquee-fade-right-yellow',
      ThemeVariant::LIGHT => 'marquee-fade-right-light',
      default => 'marquee-fade-right',
  };
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}" :archPosition="$archPositionValue">
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
        {{-- Fade gradients --}}
      <div class="absolute left-0 top-0 bottom-0 w-16 {{ $fadeLeftClass }} z-10 pointer-events-none"></div>
      <div class="absolute right-0 top-0 bottom-0 w-16 {{ $fadeRightClass }} z-10 pointer-events-none"></div>

        {{-- Lane 1: Left to Right --}}
        <div class="marquee__lane mb-3.5 overflow-hidden">
          <div class="marquee__inner" id="{{ $marqueeId }}-lane1">
            @for ($i = 0; $i < 3; $i++)
              <div class="marquee__part flex items-center gap-3.5">
                @foreach($lane1Logos as $logo)
                  @php
                    $logo_image = $logo['logo'] ?? null;
                    $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Logo');
                    $logo_url = $logo_image['url'] ?? '';
                  @endphp

                  @if(!empty($logo_url))
                    <div class="flex items-center justify-center{{ $loop->last ? ' mr-3.5' : '' }}">
                      <img
                        src="{{ $logo_url }}"
                        alt="{{ $alt_text }}"
                        class="h-28 w-auto max-w-full object-contain opacity-90 hover:opacity-100 transition-opacity"
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
            @for ($i = 0; $i < 3; $i++)
              <div class="marquee__part flex items-center gap-3.5">
                @foreach($lane2Logos as $logo)
                  @php
                    $logo_image = $logo['logo'] ?? null;
                    $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Logo');
                    $logo_url = $logo_image['url'] ?? '';
                  @endphp

                  @if(!empty($logo_url))
                    <div class="flex items-center justify-center{{ $loop->last ? ' mr-3.5' : '' }}">
                      <img
                        src="{{ $logo_url }}"
                        alt="{{ $alt_text }}"
                        class="h-28 w-auto max-w-full object-contain opacity-90 hover:opacity-100 transition-opacity"
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
            @for ($i = 0; $i < 3; $i++)
              <div class="marquee__part flex items-center gap-3.5">
                @foreach($lane3Logos as $logo)
                  @php
                    $logo_image = $logo['logo'] ?? null;
                    $alt_text = $logo['alt_text'] ?? ($logo_image['alt'] ?? 'Logo');
                    $logo_url = $logo_image['url'] ?? '';
                  @endphp

                  @if(!empty($logo_url))
                    <div class="flex items-center justify-center{{ $loop->last ? ' mr-3.5' : '' }}">
                      <img
                        src="{{ $logo_url }}"
                        alt="{{ $alt_text }}"
                        class="h-28 w-auto max-w-full object-contain opacity-90 hover:opacity-100 transition-opacity"
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
          const logoCount = {{ count($marquee_logos) }};

          // Calculate duration: base speed + (logos * speed per logo)
          // Formula: 20 seconds base + (logoCount * 0.6 seconds per logo)
          // This gives ~60s for 68 logos, ~26s for 10 logos
          const baseDuration = 20 + (logoCount * 0.6);

          // Modern GSAP initialization using centralized manager
          const initShowcaseMarquee = () => {
            const lanes = [
              { selector: `#${marqueeId}-lane1`, direction: 1, duration: baseDuration },
              { selector: `#${marqueeId}-lane2`, direction: -1, duration: baseDuration + 5 },
              { selector: `#${marqueeId}-lane3`, direction: 1, duration: baseDuration }
            ];

            const animations = [];

            lanes.forEach(({ selector, direction, duration }) => {
              const element = document.querySelector(selector);
              if (!element) return;

              // Add CSS optimization
              element.style.willChange = 'transform';

              // Modern GSAP animation - no timeline needed for simple infinite loops
              const parts = element.querySelectorAll('.marquee__part');
              if (parts.length && !element.dataset.marqueeCloned) {
                element.appendChild(parts[0].cloneNode(true));

                if (direction === -1) {
                  const lastPartClone = parts[parts.length - 1].cloneNode(true);
                  element.insertBefore(lastPartClone, element.firstElementChild);
                }

                element.dataset.marqueeCloned = 'true';
              }

              // Modern GSAP animation - no timeline needed for simple infinite loops
              const startX = direction === -1 ? '-50%' : 0;
              const endX = direction === 1 ? '-50%' : 0;
              const animation = window.gsap.fromTo(
                element,
                { x: startX },
                {
                  x: endX,
                  duration: duration,
                  ease: "none",
                  repeat: -1,
                  force3D: true
                }
              );

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
      @php
        $gridId = 'showcase-grid-' . uniqid();
      @endphp
      <div class="mb-24">
        <div id="{{ $gridId }}" class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-3">
          @foreach($grid_items as $index => $item)
            @php
              $item_image = $item['grid_image'] ?? null;
              $item_title = $item['grid_title'] ?? '';
              $item_description = $item['grid_description'] ?? '';
              $image_url = is_array($item_image) ? ($item_image['url'] ?? '') : '';
              $image_alt = is_array($item_image) ? ($item_image['alt'] ?? $item_title) : $item_title;

              // Determine layout: even index (0, 2) = Text left, Image right | odd index (1, 3) = Image left, Text right
              $isTextFirst = $index % 2 === 0;
            @endphp

            <div class="showcase-grid-item group relative overflow-hidden">
              @if($isTextFirst)
                {{-- Even index (0, 2): Text then Image (side by side on desktop, stacked on mobile) --}}
                <div class="flex flex-col md:flex-row gap-4 md:gap-6 items-center">
                  <div class="showcase-grid-text flex-1 text-left">
                    @if(!empty($item_title))
                      <x-heading
                        :as="HeadingTag::H2"
                        :size="HeadingSize::H2_MEDIUM"
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
                      >
                        {{ $item_description }}
                      </x-text>
                    @endif
                  </div>

                  @if(!empty($image_url))
                    <div class="showcase-grid-image flex-1 max-w-3xs">
                      <img
                        src="{{ $image_url }}"
                        alt="{{ $image_alt }}"
                        class="w-full h-auto rounded-lg object-cover"
                      />
                    </div>
                  @endif
                </div>
              @else
                {{-- Odd index (1, 3): Image then Text (side by side on desktop, stacked on mobile) --}}
                <div class="flex flex-col md:flex-row gap-4 md:gap-6 items-center">
                  @if(!empty($image_url))
                    <div class="showcase-grid-image flex-1 max-w-3xs">
                      <img
                        src="{{ $image_url }}"
                        alt="{{ $image_alt }}"
                        class="w-full h-auto rounded-lg object-cover"
                      />
                    </div>
                  @endif

                  <div class="showcase-grid-text flex-1 text-left">
                    @if(!empty($item_title))
                      <x-heading
                        :as="HeadingTag::H2"
                        :size="HeadingSize::H2_MEDIUM"
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

{{-- Grid Highlight Animation - Must be at bottom after GSAP loads --}}
@if($media_type === 'grid' && !empty($grid_items) && count($grid_items) === 4)
<script>
(function() {
  const gridId = '{{ $gridId }}';

  // Wait for GSAP to be available
  const waitForGSAP = (callback, maxAttempts = 50) => {
    let attempts = 0;
    const checkGSAP = () => {
      attempts++;
      if (window.gsap && window.GSAPManager) {
        callback();
      } else if (attempts < maxAttempts) {
        setTimeout(checkGSAP, 100);
      } else {
        console.error('🎨 GSAP failed to load after', attempts, 'attempts');
      }
    };
    checkGSAP();
  };

  const initGridHighlight = () => {
    const gridContainer = document.getElementById(gridId);
    if (!gridContainer) {
      return;
    }

    const items = gridContainer.querySelectorAll('.showcase-grid-item');
    if (items.length === 0) {
      return;
    }

    // Remove CSS transitions that might conflict
    items.forEach(item => {
      item.style.transition = 'none';
    });

    // Create timeline
    const timeline = window.gsap.timeline({
      repeat: -1,
      repeatDelay: 0
    });

    // Cycle through each item
    items.forEach((item, index) => {
      timeline.to(item, {
        opacity: 1,
        duration: 0.5,
        ease: "power2.inOut"
      }, index * 3);

      timeline.to(item, {
        opacity: 0.6,
        duration: 0.5,
        ease: "power2.inOut"
      }, index * 3 + 2.5);
    });

    // Set initial state
    items.forEach((item, index) => {
      window.gsap.set(item, { opacity: index === 0 ? 1 : 0.6 });
    });


    // Hover handlers
    let leaveTimeout;

    items.forEach((item, index) => {
    item.addEventListener('mouseenter', () => {
    // Clear any pending leave timeout
    if (leaveTimeout) {
      clearTimeout(leaveTimeout);
      leaveTimeout = null;
    }

    timeline.pause();

    // Dim ALL items first
    items.forEach(i => {
      window.gsap.to(i, {
        opacity: 0.6,
        duration: 0.3,
        ease: "power2.out"
      });
    });

    // Then highlight only the hovered item
    window.gsap.to(item, {
      opacity: 1,
      duration: 0.3,
      ease: "power2.out"
    });
  });

    item.addEventListener('mouseleave', () => {
      // Add delay before resetting
      leaveTimeout = setTimeout(() => {
        // Animate all items to initial state
        items.forEach((i, idx) => {
          window.gsap.to(i, {
            opacity: idx === 0 ? 1 : 0.6,
            duration: 0.3,
            ease: "power2.out"
          });
        });

        // Restart timeline after animation completes
        setTimeout(() => {
          timeline.restart();
        }, 300); // Match the animation duration
      }, 400); // Delay before reset starts
    });
  });

    return () => {
      timeline.kill();
      items.forEach(item => {
        item.style.transition = '';
        window.gsap.set(item, { clearProps: 'opacity' });
      });
    };
  };

  // Wait for GSAP then initialize
  waitForGSAP(() => {
    if (window.GSAPManager) {
      window.GSAPManager.register(
        `showcase-grid-${gridId}`,
        initGridHighlight
      );
    } else {
      initGridHighlight();
    }
  });
})();
</script>
@endif
