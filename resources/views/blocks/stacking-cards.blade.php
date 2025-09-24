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
  use App\Enums\FontType;

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
      'green' => 'stacking-cards-gradient',
      default => 'bg-white',
  };

  // Theme-based color classes
  $cardBgColor = $theme === 'dark' ? 'bg-primary-light' : 'bg-white';
  $cardTextColor = $theme === 'dark' ? 'text-primary-dark' : 'text-primary-navy';
  $eyebrowClasses = $theme === 'dark' ? 'text-primary-lime border-primary-lime' : 'text-primary-purple border-primary-purple';

  // Unique ID for this block instance
  $blockId = 'stacking-cards-' . uniqid();
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">

  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$themeVariant"
      classes="mb-12"
    />
  @endif

  @php
    $hasValidCards = !empty($cards) && is_array($cards) && count($cards) > 0;
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
            <div class="relative min-h-screen flex items-center justify-center max-w-7xl mx-auto px-4">
              <div class="stacking-cards-wrapper relative w-full" style="height: 800px;">
                @foreach($cards as $index => $card)
                  <div class="stacking-card absolute w-full transition-transform duration-700 ease-out"
                      data-card-index="{{ $index }}"
                      style="z-index: {{ 10 + $index }}; top: 0; left: 0; transform: translateY(100vh);">
                    <div class="flex flex-row rounded-[32px] overflow-hidden relative {{ $cardBgColor }} shadow-2xl">
                      <div class="w-2/5 flex-shrink-0">
                        <img src="{{ $card['image']['url'] }}" alt="{{ $card['image']['alt'] ?? $card['title'] }}" class="w-full h-full object-cover min-h-[400px]"/>
                      </div>
                      <div class="flex flex-col flex-1 p-8 lg:p-12 min-h-[400px] justify-center">
                        <div class="space-y-4">
                          <x-heading
                            :as="HeadingTag::H3"
                            :size="HeadingSize::H3"
                            :font="FontType::SANS"
                            class="{{ $cardTextColor }} mb-4"
                          >
                            {{ $card['title'] }}
                          </x-heading>

                          <x-text
                            :as="TextTag::P"
                            :size="TextSize::BASE"
                            class="{{ $cardTextColor }} leading-relaxed"
                          >
                            {!! $card['description'] !!}
                          </x-text>
                        </div>

                        @if(!empty($card['cta']))
                          <div class="pt-8">
                            <a href="{{ $card['cta']['url'] }}"
                              class="{{ $cardTextColor }} hover:text-primary-orange text-base font-medium underline underline-offset-4"
                              target="{{ $card['cta']['target'] ?? '_self' }}">
                              {{ $card['cta']['title'] ?? 'Learn more' }}
                            </a>
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
              <div class="flex flex-col rounded-[32px] overflow-hidden relative {{ $cardBgColor }} shadow-lg">
                <div>
                  <img src="{{ $card['image']['url'] }}" alt="{{ $card['image']['alt'] ?? $card['title'] }}" class="w-full h-auto object-cover rounded-tl-[32px] rounded-tr-[32px]">
                </div>
                <div class="flex flex-col flex-1 p-6 min-h-[250px] rounded-bl-[32px] rounded-br-[32px] overflow-hidden">
                  <div class="mb-auto space-y-4">
                    <x-heading
                      :as="HeadingTag::H3"
                      :size="HeadingSize::H4"
                      :font="FontType::SANS"
                      class="{{ $cardTextColor }}"
                    >
                      {{ $card['title'] }}
                    </x-heading>

                    <x-text
                      :as="TextTag::P"
                      :size="TextSize::BASE"
                      class="{{ $cardTextColor }} leading-relaxed"
                    >
                      {!! $card['description'] !!}
                    </x-text>
                  </div>

                  @if(!empty($card['cta']))
                    <div class="pt-6">
                      <a href="{{ $card['cta']['url'] }}"
                         class="{{ $cardTextColor }} hover:text-primary-orange text-sm font-normal underline underline-offset-4"
                         target="{{ $card['cta']['target'] ?? '_self' }}">
                        {{ $card['cta']['title'] ?? 'Learn more' }}
                      </a>
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
    <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded max-w-md mx-auto">
      <p><strong>No cards available.</strong></p>
      <p>Please add at least 1 card in the block editor.</p>
    </div>
  @endif
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

  // Wait for GSAP to be ready
  if (window.gsap && window.ScrollTrigger) {
    initScrollTrigger();
  } else {
    // Wait for app.js to load GSAP
    setTimeout(() => {
      if (window.gsap && window.ScrollTrigger) {
        initScrollTrigger();
      }
    }, 1000);
  }
})();
</script>
@endif
