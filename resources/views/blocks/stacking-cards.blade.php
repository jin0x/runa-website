@php
  /**
   * Stacking Cards Block
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Enums\FontType;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Theme-based color classes
  $cardTextColor = $themeVariant === ThemeVariant::DARK ? TextColor::LIGHT : TextColor::DARK;

  // Unique ID for this block instance
  $blockId = 'stacking-cards-' . uniqid();
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  <x-container>

    @if($section_eyebrow || $section_title || $section_description)
      <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_title"
        :subtitle="$section_description"
        :variant="$sectionHeadingVariant"
        classes="mb-12"
      />
    @endif

    @php
      $hasValidCards = !empty($cards) && is_array($cards) && count  ($cards) > 0;
    @endphp

    @if($hasValidCards)
      <!-- Stacking Cards Container -->
      <div
        id="{{ $blockId }}"
        class="stacking-cards-container"
        data-cards="{{ base64_encode(json_encode($cards)) }}"
        data-mobile-breakpoint="{{ $mobile_breakpoint }}"
      >
        <!-- Desktop Layout -->
        <div class="hidden lg:block">
          <div class="relative">
            <!-- Cards Container -->
            <div class="stacking-cards-content">
              <div class="relative min-h-screen flex items-center justify-center  max-w-7xl mx-auto px-4">
                <div class="stacking-cards-wrapper relative w-full" style="height:  800px;">
                  @foreach($cards as $index => $card)
                  @php
                      // Cycle through 4 colors using ThemeVariant constants
                      $colorVariant = match($index % 4) {
                        0 => ThemeVariant::CYAN,
                        1 => ThemeVariant::GREEN,
                        2 => ThemeVariant::PURPLE,
                        3 => ThemeVariant::YELLOW,
                      };
                      $bgColor = EnumHelper::getCardBackgroundClass ($colorVariant);
                    @endphp
                    <div class="stacking-card absolute w-full transition-transform  duration-700 ease-out"
                        data-card-index="{{ $index }}"
                        style="z-index: {{ 10 + $index }}; top: 0; left: 0;   transform: translateY(100vh);">
                      <div class="flex flex-row rounded-[32px] overflow-hidden  relative shadow-2xl  {{ $bgColor }}">
                        <div class="w-2/5 flex-shrink-0 py-6 pl-6">
                          <img src="{{ $card['image']['url'] }}" alt="{{ $card  ['image']['alt'] ?? $card['title'] }}" class="w-full h-full   object-cover min-h-[400px]"/>
                        </div>
                        <div class="flex flex-col flex-1 p-8 lg:p-12 min-h-[400px]  justify-center">
                          <div class="space-y-4">
                            <x-heading
                              :as="HeadingTag::H2"
                              :size="HeadingSize::H2"
                              :font="FontType::SANS"
                              :color="$cardTextColor"
                              class="mb-4"
                            >
                              {{ $card['title'] }}
                            </x-heading>

                            <x-text
                              :as="TextTag::P"
                              :size="TextSize::BASE"
                              :color="$cardTextColor"
                              class="leading-relaxed"
                            >
                              {!! $card['description'] !!}
                            </x-text>
                          </div>

                          @if(!empty($card['cta']))
                            <div class="pt-8">
                              <x-text
                                :as="TextTag::A"
                                :size="TextSize::BASE"
                                :color="$cardTextColor"
                                class="font-medium underline underline-offset-4   hover:opacity-75 transition-opacity"
                                href="{{ $card['cta']['url'] }}"
                                target="{{ $card['cta']['target'] ?? '_self' }}"
                              >
                                {{ $card['cta']['title'] ?? 'Learn more' }}
                              </x-text>
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mobile Layout (Simple Stack) -->
        <div class="block lg:hidden">
          <div class="stacking-mobile-container space-y-8">
            @foreach($cards as $card)
              <div class="mobile-card max-w-md mx-auto">
                <div class="flex flex-col rounded-[32px] overflow-hidden relative   bg-white shadow-lg">
                  <div>
                    <img src="{{ $card['image']['url'] }}" alt="{{ $card['image'] ['alt'] ?? $card['title'] }}" class="w-full h-auto object-cover  rounded-tl-[32px] rounded-tr-[32px]">
                  </div>
                  <div class="flex flex-col flex-1 p-6 min-h-[250px] rounded-bl-  [32px] rounded-br-[32px] overflow-hidden">
                    <div class="mb-auto space-y-4">
                      <x-heading
                        :as="HeadingTag::H3"
                        :size="HeadingSize::H4"
                        :font="FontType::SANS"
                        :color="$cardTextColor"
                      >
                        {{ $card['title'] }}
                      </x-heading>

                      <x-text
                        :as="TextTag::P"
                        :size="TextSize::BASE"
                        :color="$cardTextColor"
                        class="leading-relaxed"
                      >
                        {!! $card['description'] !!}
                      </x-text>
                    </div>

                    @if(!empty($card['cta']))
                      <div class="pt-6">
                        <x-text
                          :as="TextTag::A"
                          :size="TextSize::SMALL"
                          :color="$cardTextColor"
                          class="font-normal underline underline-offset-4   hover:opacity-75 transition-opacity"
                          href="{{ $card['cta']['url'] }}"
                          target="{{ $card['cta']['target'] ?? '_self' }}"
                        >
                          {{ $card['cta']['title'] ?? 'Learn more' }}
                        </x-text>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @else
      <!-- No cards available -->
      <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded  max-w-md mx-auto">
        <p><strong>No cards available.</strong></p>
        <p>Please add at least 1 card in the block editor.</p>
      </div>
    @endif
  </x-container>
</x-section>

@if($hasValidCards)
<script>
(function() {
  const blockId = '{{ $blockId }}';
  const container = document.getElementById(blockId);

  if (!container) {
    console.error('Stacking cards container not found:', blockId);
    return;
  }

  // Decode data from base64
  const encodedData = container.dataset.cards;
  const mobileBreakpoint = parseInt(container.dataset.mobileBreakpoint) || 996;

  let cards;
  try {
    cards = JSON.parse(atob(encodedData));
  } catch (e) {
    console.error('Failed to decode cards data:', e);
    return;
  }

  // DOM elements
  const cardElements = container.querySelectorAll('.stacking-card[data-card-index]');
  const cardsWrapper = container.querySelector('.stacking-cards-wrapper');

  let activeCard = 0;
  let isMobile = window.innerWidth <= mobileBreakpoint;

  function updateActiveCard(progress) {
    // Calculate which cards should be visible based on scroll progress
    const totalCards = cards.length;
    const cardProgress = progress * totalCards;

    cardElements.forEach((card, index) => {
      const cardIndex = parseInt(card.dataset.cardIndex);
      const cardThreshold = cardIndex / totalCards;

      if (progress >= cardThreshold) {
        // Card should be visible and in position
        const stackOffset = cardIndex * 100; // 100px offset between cards
        card.style.transform = `translateY(${stackOffset}px)`;
        card.style.opacity = '1';
      } else if (progress >= (cardThreshold - 0.1)) {
        // Card is sliding in
        const slideProgress = (progress - (cardThreshold - 0.1)) / 0.1;
        const yPos = (1 - slideProgress) * window.innerHeight + cardIndex * 100;
        card.style.transform = `translateY(${yPos}px)`;
        card.style.opacity = '1';
      } else {
        // Card is below viewport
        card.style.transform = `translateY(${window.innerHeight}px)`;
        card.style.opacity = '1';
      }
    });
  }

  function checkMobile() {
    isMobile = window.innerWidth <= mobileBreakpoint;
  }

  function initScrollTrigger() {
    if (isMobile || !window.gsap || !window.ScrollTrigger) {
      return;
    }

    const scrollContent = container.querySelector('.stacking-cards-content');
    if (!scrollContent) return;

    window.ScrollTrigger.create({
      trigger: scrollContent,
      start: "top top",
      end: `+=${cards.length * window.innerHeight * 1.5}`,
      scrub: 1.5,
      pin: true,
      anticipatePin: 1,
      onUpdate: (self) => {
        updateActiveCard(self.progress);
      }
    });
  }

  // Initialize
  checkMobile();

  // Debug: Log card elements found
  console.log('Stacking Cards Debug:', {
    blockId,
    cardElements: cardElements.length,
    cards: cards.length,
    isMobile,
    container
  });

  // Show animation progression for testing
  setTimeout(() => {
    if (!isMobile && cardElements.length > 1) {
      console.log('Test: Showing cards animation');
      // Animate through 50% progress
      updateActiveCard(0.5);
    }
  }, 2000);

  window.addEventListener('resize', () => {
    checkMobile();
    // Refresh ScrollTrigger on resize
    if (window.ScrollTrigger) {
      window.ScrollTrigger.refresh();
    }
  });

  // Register with GSAPManager for proper initialization
  if (window.GSAPManager) {
    window.GSAPManager.register(
      `stacking-cards-${blockId}`,
      () => {
        initScrollTrigger();
        // Return cleanup function
        return () => {
          if (window.ScrollTrigger) {
            window.ScrollTrigger.getAll().forEach(trigger => {
              if (trigger.trigger && trigger.trigger.closest(`#${blockId}`)) {
                trigger.kill();
              }
            });
          }
        };
      }
    );
  } else {
    // Fallback for legacy initialization
    console.warn('GSAPManager not available for stacking cards, using legacy initialization');
    if (window.gsap && window.ScrollTrigger) {
      initScrollTrigger();
    } else {
      setTimeout(() => {
        if (window.gsap && window.ScrollTrigger) {
          initScrollTrigger();
        }
      }, 1000);
    }
  }
})();
</script>
@endif
