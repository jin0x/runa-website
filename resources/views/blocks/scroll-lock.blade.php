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
  use App\Enums\SectionHeadingVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Theme-based color enums
  $headingColor = $themeVariant === ThemeVariant::DARK ? TextColor::DARK : TextColor::LIGHT;
  $textColor = $themeVariant === ThemeVariant::DARK ? TextColor::DARK : TextColor::GRAY;

  // Unique ID for this block instance
  $blockId = 'scroll-lock-' . uniqid();
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
      $hasValidSections = !empty($sections) && is_array($sections) && count ($sections) > 0;
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-start max-w-7xl mx-auto px-4">
              <!-- Left Content -->
              <div class="scroll-content-container relative min-h-[400px]">
                @foreach($sections as $index => $section)
                  @php
                    // Cycle through 4 colors: green, cyan, yellow, purple
                    $titleColor = match($index % 4) {
                      0 => 'text-primary-green-neon',
                      1 => 'text-secondary-cyan',
                      2 => 'text-primary-yellow',
                      3 => 'text-secondary-purple',
                    };

                    // Progress bar widths
                    $progressWidth = match($index % 4) {
                        0 => '80%',
                        1 => '60%',
                        2 => '40%',
                        3 => '20%',
                    };

                    // Background color classes for the bar
                    $barColorClass = match($index % 4) {
                        0 => 'bg-primary-green-neon',
                        1 => 'bg-secondary-cyan',
                        2 => 'bg-primary-yellow',
                        3 => 'bg-secondary-purple',
                    };
                  @endphp

                  <div class="scroll-section mb-8 transition-all duration-700 ease-in-out cursor-pointer hover:opacity-100 {{ $index === 0 ? 'opacity-100 is-active' : 'opacity-60' }}" data-section-index="{{ $index }}" data-color-class="{{ $titleColor }}" data-bar-color="{{ $barColorClass }}" data-bar-width="{{ $progressWidth }}">
                    <x-heading
                      :as="HeadingTag::H3"
                      :size="HeadingSize::H3"
                      :color="$headingColor"
                      class="mb-6 section-title {{ $index === 0 ? $titleColor : '' }}"
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

                    @if(!empty($section['link']) && !empty($section['link']['url']))
                      <div class="mt-6">
                        <x-button
                          :href="$section['link']['url']"
                          :target="$section['link']['target'] ?? '_self'"
                          variant="primary"
                          size="md"
                        >
                          {{ $section['link']['title'] ?? 'Learn More' }}
                        </x-button>
                      </div>
                    @endif

                    {{-- Animated Progress Bar --}}
                    <div class="my-8 w-full h-1 bg-white/20 rounded-full overflow-hidden">
                      <div class="section-progress-bar h-full rounded-full {{ $barColorClass }}"
                          style="width: 0%; transition: none;"
                          data-duration="2500"></div>
                    </div>
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
        <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3  rounded">
          <p><strong>No content sections available.</strong></p>
          <p>Please add at least 2 sections in the block editor.</p>
        </div>
      @endif
  </x-container>
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

  let activeSection = -1; // Start at -1 to force initial activation
  let isMobile = window.innerWidth <= mobileBreakpoint;
  let autoScrollInterval = null;
  let isUserInteracting = false;
  const AUTO_SCROLL_DELAY = 3500; // 3.5 seconds per item

  function updateActiveSection(newSection, fromUser = false) {
    if (newSection === activeSection) {
      return;
    }

    // If user interaction, pause auto-scroll temporarily
    if (fromUser) {
      isUserInteracting = true;
      clearAutoScroll();
      // Resume after 10 seconds of inactivity
      setTimeout(() => {
        isUserInteracting = false;
        startAutoScroll();
      }, 10000);
    }

    // Update content sections highlighting
    contentSections.forEach((section, index) => {
      const title = section.querySelector('.section-title');
      const progressBar = section.querySelector('.section-progress-bar');
      const colorClass = section.dataset.colorClass;
      const barColor = section.dataset.barColor;
      const duration = parseInt(progressBar?.dataset.duration || AUTO_SCROLL_DELAY);

      if (index === newSection) {
        // Active section styles
        section.classList.remove('opacity-60');
        section.classList.add('opacity-100', 'is-active');

        if (title && colorClass) {
          title.classList.remove('text-white');
          title.classList.add(colorClass);
        }

        // Animate progress bar from 0 to 100%
        if (progressBar && barColor) {
          progressBar.classList.remove('bg-white/20', 'bg-primary-green-neon', 'bg-secondary-cyan', 'bg-primary-yellow', 'bg-secondary-purple');
          progressBar.classList.add(barColor);

          // Reset to 0
          progressBar.style.transition = 'none';
          progressBar.style.width = '0%';

          // Force reflow
          progressBar.offsetHeight;

          // Animate to 100%
          progressBar.style.transition = `width ${duration}ms linear`;
          progressBar.style.width = '100%';
        }
      } else {
        section.classList.remove('opacity-100', 'is-active');
        section.classList.add('opacity-60');

        if (title && colorClass) {
          title.classList.remove(colorClass);
          title.classList.remove('text-white');
        }

        // Reset progress bar
        if (progressBar) {
          progressBar.style.transition = 'width 300ms ease-out';
          progressBar.style.width = '0%';
        }
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

  // Auto-scroll functions
  function startAutoScroll() {

    if (isMobile || isUserInteracting) {
      return;
    }

    clearAutoScroll();
    autoScrollInterval = setInterval(() => {
      if (!isUserInteracting) {
        const nextSection = (activeSection + 1) % sections.length;
        updateActiveSection(nextSection, false);
      }
    }, AUTO_SCROLL_DELAY);
  }

  function clearAutoScroll() {
    if (autoScrollInterval) {
      clearInterval(autoScrollInterval);
      autoScrollInterval = null;
    }
  }

  // Add click handlers to sections
  function initClickHandlers() {
    contentSections.forEach((section, index) => {
      section.addEventListener('click', () => {
        updateActiveSection(index, true);
      });
    });
  }

  function initScrollTrigger() {

    if (isMobile) {
      return;
    }

    if (!window.gsap || !window.ScrollTrigger) {
      return;
    }

    const scrollContent = container.querySelector('.scroll-lock-content');
    if (!scrollContent) {
      return;
    }


    // Create ScrollTrigger without pinning - just track when in view
    window.ScrollTrigger.create({
      trigger: scrollContent,
      start: "top center",
      end: "bottom center",
      onEnter: () => {
        // Start auto-scroll when entering the section
        if (!isUserInteracting) {
          startAutoScroll();
        }
      },
      onLeave: () => {
        // Stop auto-scroll when leaving the section
        clearAutoScroll();
      },
      onEnterBack: () => {
        // Restart auto-scroll when scrolling back into view
        if (!isUserInteracting) {
          startAutoScroll();
        }
      },
      onLeaveBack: () => {
        // Stop auto-scroll when scrolling back out
        clearAutoScroll();
      }
    });
  }

  checkMobile();
  initClickHandlers();

  // Trigger initial state on desktop
  if (!isMobile) {
    // Small delay to ensure DOM is ready
    setTimeout(() => {
      updateActiveSection(0, false);
    }, 100);
  }

  window.addEventListener('resize', () => {
    const wasMobile = isMobile;
    checkMobile();

    // If switching between mobile and desktop
    if (wasMobile !== isMobile) {
      clearAutoScroll();
      if (!isMobile) {
        startAutoScroll();
      }
    }

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
          clearAutoScroll();
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

  // Cleanup on page unload
  window.addEventListener('beforeunload', () => {
    clearAutoScroll();
  });
})();
</script>
@endif
