@php
  /**
   * Scroll Lock Block
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
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

  // Set ThemeVariant for section background
  $sectionVariant = match ($theme) {
      'dark' => ThemeVariant::DARK,
      'green' => ThemeVariant::GREEN_GRADIENT,
      default => ThemeVariant::LIGHT,
  };

  // Theme-based color enums
  $headingColor = $theme === 'dark' ? TextColor::LIGHT : TextColor::DARK;
  $textColor = $theme === 'dark' ? TextColor::LIGHT : TextColor::GRAY;

  // Unique ID for this block instance
  $blockId = 'scroll-lock-' . uniqid();
@endphp

<x-section :size="$sectionSizeValue" :variant="$sectionVariant" classes="{{ $block->classes }}">

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
    $hasValidSections = !empty($sections) && is_array($sections) && count($sections) > 0;
  @endphp

  @if($hasValidSections)
    <!-- Desktop Scroll Lock Component -->
    <div
      id="{{ $blockId }}"
      class="scroll-lock-container"
      data-sections="{{ base64_encode(json_encode($sections)) }}"
      data-mobile-breakpoint="{{ $mobile_breakpoint }}"
    >
      <!-- Desktop Layout -->
      <div class="hidden lg:block">
        <div class="relative">
          <!-- Content Container -->
          <div class="scroll-lock-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center min-h-screen max-w-7xl mx-auto px-4">
              <!-- Left Content -->
              <div class="scroll-content-container relative min-h-[400px]">
                @foreach($sections as $index => $section)
                  <div class="scroll-section mb-8 transition-all duration-700 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-60' }}" data-section-index="{{ $index }}">
                    <x-heading
                      :as="HeadingTag::H3"
                      :size="HeadingSize::H5"
                      :color="$headingColor"
                      class="mb-6"
                    >
                      {{ $section['title'] }}
                    </x-heading>

                    <x-text
                      :as="TextTag::DIV"
                      :size="TextSize::SMALL"
                      :color="$textColor"
                      class="leading-relaxed"
                    >
                      {!! $section['description'] !!}
                    </x-text>
                  </div>
                @endforeach
              </div>

              <!-- Right Image -->
              <div class="flex items-center justify-center">
                <div class="scroll-image-container relative w-full h-96 lg:h-[600px] overflow-hidden rounded-lg shadow-lg">
                    @foreach($sections as $index => $section)
                      <div class="absolute inset-0 transition-all duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-95' }}" data-image-index="{{ $index }}">
                        <img src="{{ $section['image']['url'] }}" alt="{{ $section['image']['alt'] ?? $section['title'] }}" class="w-full h-full object-cover">
                      </div>
                    @endforeach
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Mobile Layout (Stacked) -->
      <div class="block lg:hidden">
        <div class="scroll-mobile-container space-y-12">
          @foreach($sections as $section)
            <div class="mobile-section">
              <div class="mb-6">
                <img src="{{ $section['image']['url'] }}" alt="{{ $section['image']['alt'] ?? $section['title'] }}" class="w-full h-auto object-cover rounded-lg">
              </div>
              <div>
                <x-heading
                  :as="\App\Enums\HeadingTag::H3"
                  :size="\App\Enums\HeadingSize::H4"
                  :color="$headingColor"
                  class="mb-4"
                >
                  {{ $section['title'] }}
                </x-heading>

                <x-text
                  :as="\App\Enums\TextTag::DIV"
                  :size="\App\Enums\TextSize::BASE"
                  :color="$textColor"
                  class="leading-relaxed"
                >
                  {!! $section['description'] !!}
                </x-text>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @else
    <!-- No sections available -->
    <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded">
      <p><strong>No content sections available.</strong></p>
      <p>Please add at least 2 sections in the block editor.</p>
    </div>
  @endif
</x-section>

@if($hasValidSections)
<script>
(function() {
  const blockId = '{{ $blockId }}';
  const container = document.getElementById(blockId);

  if (!container) {
    console.error('Scroll lock container not found:', blockId);
    return;
  }


  // Decode data from base64
  const encodedData = container.dataset.sections;
  const mobileBreakpoint = parseInt(container.dataset.mobileBreakpoint) || 996;

  let sections;
  try {
    sections = JSON.parse(atob(encodedData));
  } catch (e) {
    console.error('Failed to decode sections data:', e);
    return;
  }

  // DOM elements
  const contentSections = container.querySelectorAll('.scroll-section[data-section-index]');
  const imageSections = container.querySelectorAll('[data-image-index]');

  let activeSection = 0;
  let isMobile = window.innerWidth <= mobileBreakpoint;

  function updateActiveSection(newSection) {
    if (newSection === activeSection) return;

    // Update content sections highlighting
    contentSections.forEach((section, index) => {
      if (index === newSection) {
        section.classList.remove('opacity-60');
        section.classList.add('opacity-100');
      } else {
        section.classList.remove('opacity-100');
        section.classList.add('opacity-60');
      }
    });

    // Update image sections visibility
    imageSections.forEach((section, index) => {
      if (index === newSection) {
        section.classList.remove('opacity-0', 'scale-95');
        section.classList.add('opacity-100', 'scale-100');
      } else {
        section.classList.remove('opacity-100', 'scale-100');
        section.classList.add('opacity-0', 'scale-95');
      }
    });

    activeSection = newSection;
  }

  function checkMobile() {
    isMobile = window.innerWidth <= mobileBreakpoint;
  }

  function initScrollTrigger() {
    if (isMobile || !window.gsap || !window.ScrollTrigger) {
      return;
    }

    const scrollContent = container.querySelector('.scroll-lock-content');
    if (!scrollContent) return;

    window.ScrollTrigger.create({
      trigger: scrollContent,
      start: "top top",
      end: `+=${sections.length * window.innerHeight * 1.5}`,
      scrub: 2,
      pin: true,
      anticipatePin: 1,
      onUpdate: (self) => {
        const progress = self.progress;
        const rawSectionProgress = progress * sections.length;
        const currentSectionIndex = Math.floor(rawSectionProgress);
        const sectionProgress = rawSectionProgress - currentSectionIndex;

        let targetSection = currentSectionIndex;
        if (sectionProgress > 0.25) {
          targetSection = Math.min(currentSectionIndex + 1, sections.length - 1);
        }

        updateActiveSection(targetSection);
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

  // Register with GSAPManager for proper initialization
  if (window.GSAPManager) {
    window.GSAPManager.register(
      `scroll-lock-${blockId}`,
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
    console.warn('GSAPManager not available for scroll lock, using legacy initialization');
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
