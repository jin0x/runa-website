@php
  /**
   * Scroll Lock Block
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
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

  // Set background color based on theme
  $bgColor = match ($theme) {
      'dark' => 'bg-primary-dark',
      'green' => 'scroll-lock-gradient',
      default => 'bg-white',
  };

  // Theme-based color classes
  $eyebrowClasses = $theme === 'dark' ? 'text-primary-lime border-primary-lime' : 'text-primary-purple border-primary-purple';
  $headingClasses = $theme === 'dark' ? 'text-white' : 'text-primary-navy';
  $textClasses = $theme === 'dark' ? 'text-primary-light' : 'text-neutral-700';
  $progressBarClasses = $theme === 'dark' ? 'bg-primary-lime' : 'bg-primary-purple';
  $progressTrackClasses = $theme === 'dark' ? 'bg-primary-dark/20' : 'bg-neutral-200';

  // Unique ID for this block instance
  $blockId = 'scroll-lock-' . uniqid();
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
    // Debug: Check sections data
    $sectionsJson = json_encode($sections);
    $hasValidSections = !empty($sections) && is_array($sections) && count($sections) > 0;
  @endphp


  @if($hasValidSections)
    <!-- Desktop Scroll Lock Component -->
    <div
      id="{{ $blockId }}"
      x-data="scrollLockComponent({!! $sectionsJson !!}, {{ $mobile_breakpoint }})"
      x-init="init()"
      class="scroll-lock-container"
    >
      <!-- Desktop Layout -->
      <div class="hidden lg:block">
        <div class="relative">
          <!-- Progress Bar -->
          <div class="absolute left-0 top-0 bottom-0 w-1 {{ $progressTrackClasses }} z-10 rounded-full">
            <div
              class="w-full {{ $progressBarClasses }} transition-all duration-300 ease-out rounded-full"
              :style="`height: ${progressPercentage}%; transform: translateY(${progressOffset}px)`"
            ></div>
          </div>

          <!-- Content Container -->
          <div class="scroll-lock-content pl-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center min-h-screen max-w-7xl mx-auto px-4">
              <!-- Left Content -->
              <div class="relative min-h-[400px]">
                <template x-for="(section, index) in sections" :key="index">
                  <div
                    class="scroll-section absolute w-full"
                    :class="index === activeSection ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                    x-transition:enter="transition-opacity duration-700"
                    x-transition:leave="transition-opacity duration-700"
                    x-show="index === activeSection"
                  >
                    <h3
                      class="mb-6 {{ $headingClasses }} text-3xl lg:text-4xl font-bold"
                      x-text="section.title"
                    ></h3>

                    <div class="{{ $textClasses }} text-lg leading-relaxed" x-text="section.description"></div>
                  </div>
                </template>
              </div>

              <!-- Right Image -->
              <div class="relative">
                <div class="sticky top-1/2 transform -translate-y-1/2">
                  <div class="relative w-full h-96 lg:h-[600px] overflow-hidden rounded-lg shadow-lg">
                    <template x-for="(section, index) in sections" :key="index">
                      <div
                        :data-image-index="index"
                        class="absolute inset-0 transition-all duration-700 ease-in-out"
                        :class="index === activeSection ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
                        x-show="index === activeSection"
                      >
                        <img
                          :src="section.image.url"
                          :alt="section.image.alt || section.title"
                          class="w-full h-full object-cover"
                        />
                      </div>
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Mobile Layout (Stacked) -->
      <div class="block lg:hidden">
        <div class="space-y-12">
          <template x-for="(section, index) in sections" :key="index">
            <div class="mobile-section">
              <!-- Image First -->
              <div class="mb-6">
                <img
                  :src="section.image.url"
                  :alt="section.image.alt || section.title"
                  class="w-full h-auto object-cover rounded-lg"
                />
              </div>

              <!-- Content -->
              <div>
                <h3
                  class="mb-4 {{ $headingClasses }} text-2xl font-bold"
                  x-text="section.title"
                ></h3>

                <div class="{{ $textClasses }} text-base leading-relaxed" x-text="section.description"></div>
              </div>
            </div>
          </template>
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

<script>
  function scrollLockComponent(sectionsData, mobileBreakpoint) {
    return {
      sections: sectionsData || [],
      activeSection: 0,
      progressPercentage: 0,
      progressOffset: 0,
      isMobile: false,
      scrollTimeline: null,
      mobileBreakpoint: mobileBreakpoint || 996,

      init() {
        this.checkMobile();

        // Add resize listener
        this.resizeHandler = () => {
          this.checkMobile();
          this.handleResize();
        };
        window.addEventListener('resize', this.resizeHandler);

        // Initialize scroll trigger if not mobile
        // Debug log
        console.log('ScrollLock Init:', {
          sections: this.sections,
          isMobile: this.isMobile,
          gsapAvailable: typeof gsap !== 'undefined',
          scrollTriggerAvailable: typeof ScrollTrigger !== 'undefined'
        });

        if (!this.isMobile) {
          if (typeof gsap !== 'undefined') {
            if (typeof ScrollTrigger !== 'undefined') {
              this.$nextTick(() => {
                this.initScrollTrigger();
              });
            } else {
              console.error('ScrollTrigger not available');
            }
          } else {
            console.error('GSAP not available');
          }
        }

        // Cleanup on destroy
        this.$el.addEventListener('x-data:cleanup', () => this.destroy());
      },

      checkMobile() {
        this.isMobile = window.innerWidth <= this.mobileBreakpoint;
      },

      handleResize() {
        if (this.scrollTimeline) {
          this.scrollTimeline.kill();
          this.scrollTimeline = null;
        }

        // Debug log
        console.log('ScrollLock Init:', {
          sections: this.sections,
          isMobile: this.isMobile,
          gsapAvailable: typeof gsap !== 'undefined',
          scrollTriggerAvailable: typeof ScrollTrigger !== 'undefined'
        });

        if (!this.isMobile) {
          if (typeof gsap !== 'undefined') {
            if (typeof ScrollTrigger !== 'undefined') {
              this.$nextTick(() => {
                this.initScrollTrigger();
              });
            } else {
              console.error('ScrollTrigger not available');
            }
          } else {
            console.error('GSAP not available');
          }
        }
      },

      initScrollTrigger() {
        console.log('InitScrollTrigger called', {
          isMobile: this.isMobile,
          sectionsLength: this.sections.length
        });

        if (this.isMobile || this.sections.length === 0) {
          console.log('Skipping ScrollTrigger: mobile or no sections');
          return;
        }

        const container = this.$el.querySelector('.scroll-lock-content');

        if (!container) return;

        // Create ScrollTrigger for pinning and progress tracking
        this.scrollTimeline = ScrollTrigger.create({
          trigger: container,
          start: "top top",
          end: `+=${this.sections.length * window.innerHeight * 1.5}`, // Add extra height for traction
          scrub: 2, // Higher scrub value for more resistance/traction
          pin: true,
          anticipatePin: 1,
          onUpdate: (self) => {
            const progress = self.progress;

            // Calculate which section should be active with traction
            const rawSectionProgress = progress * this.sections.length;
            const currentSectionIndex = Math.floor(rawSectionProgress);
            const sectionProgress = rawSectionProgress - currentSectionIndex;

            // Add traction - require 25% progress before changing sections
            let targetSection = currentSectionIndex;
            if (sectionProgress > 0.25) {
              targetSection = Math.min(currentSectionIndex + 1, this.sections.length - 1);
            }

            // Smooth section transitions
            if (targetSection !== this.activeSection) {
              this.activeSection = targetSection;
            }

            // Update progress bar with smooth animation
            const smoothProgress = gsap.utils.clamp(0, 100, progress * 100);
            this.progressPercentage = smoothProgress;

            // Offset progress bar based on scroll position
            const maxOffset = container.offsetHeight - window.innerHeight;
            this.progressOffset = gsap.utils.clamp(0, maxOffset, progress * maxOffset);
          },
          onToggle: (self) => {
            if (self.isActive) {
              // Add body class for styling when scroll-locked
              document.body.classList.add('scroll-locked');
            } else {
              document.body.classList.remove('scroll-locked');
            }
          }
        });

        // Add image transition animations
        this.sections.forEach((section, index) => {
          gsap.set(`[data-image-index="${index}"]`, {
            opacity: index === 0 ? 1 : 0,
            scale: index === 0 ? 1 : 0.95
          });
        });
      },

      destroy() {
        // Remove resize listener
        if (this.resizeHandler) {
          window.removeEventListener('resize', this.resizeHandler);
        }

        // Kill scroll timeline
        if (this.scrollTimeline) {
          this.scrollTimeline.kill();
          this.scrollTimeline = null;
        }

        // Clean up any scroll triggers related to this component
        if (typeof ScrollTrigger !== 'undefined') {
          ScrollTrigger.getAll().forEach(trigger => {
            if (trigger.trigger && this.$el.contains(trigger.trigger)) {
              trigger.kill();
            }
          });
        }

        // Remove body class
        document.body.classList.remove('scroll-locked');
      }
    }
  }
</script>