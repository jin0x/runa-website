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
              <div class="stacking-cards-wrapper relative">
                @foreach($cards as $index => $card)
                  <div class="stacking-card absolute w-full max-w-md transition-all duration-700 ease-out {{ $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-95' }}"
                       data-card-index="{{ $index }}"
                       style="z-index: {{ 10 + $index }};">
                    <div class="flex flex-col rounded-[32px] overflow-hidden relative {{ $cardBgColor }} shadow-xl">
                      <div>
                        <img src="{{ $card['image']['url'] }}" alt="{{ $card['image']['alt'] ?? $card['title'] }}" class="w-full h-full object-cover min-h-[250px] max-h-[250px]"/>
                      </div>
                      <div class="flex flex-col flex-1 p-6 lg:p-8 min-h-[280px] rounded-bl-[32px] rounded-br-[32px] overflow-hidden">
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
                            :size="TextSize::SMALL"
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

  function updateActiveCard(newCard) {
    if (newCard === activeCard) return;

    // Calculate offset for stacking effect
    const offsetIncrement = 20; // 20px offset per card

    cardElements.forEach((card, index) => {
      const cardIndex = parseInt(card.dataset.cardIndex);

      if (cardIndex <= newCard) {
        // Show card
        card.classList.remove('opacity-0', 'scale-95');
        card.classList.add('opacity-100', 'scale-100');

        // Apply stacking offset
        const offset = cardIndex * offsetIncrement;
        card.style.transform = `translateY(${offset}px)`;

        // Adjust opacity for stacked effect
        const opacity = cardIndex === newCard ? 1 : 0.8 - (newCard - cardIndex) * 0.1;
        card.style.opacity = Math.max(opacity, 0.3);
      } else {
        // Hide card
        card.classList.remove('opacity-100', 'scale-100');
        card.classList.add('opacity-0', 'scale-95');
        card.style.transform = 'translateY(0px)';
      }
    });

    activeCard = newCard;
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
      end: `+=${cards.length * window.innerHeight * 1.2}`,
      scrub: 1,
      pin: true,
      anticipatePin: 1,
      onUpdate: (self) => {
        const progress = self.progress;
        const totalCards = cards.length;
        const currentCardProgress = progress * totalCards;

        // Determine which card should be active
        let targetCard = Math.floor(currentCardProgress);

        // Add some buffer to prevent rapid switching
        if (currentCardProgress - targetCard > 0.3) {
          targetCard = Math.min(targetCard + 1, totalCards - 1);
        }

        updateActiveCard(targetCard);
      }
    });
  }

  // Initialize
  checkMobile();

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