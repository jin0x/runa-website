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
  <!-- ALPINE TEST COMPONENT -->
  <div x-data="testCounter" style="background: yellow; padding: 20px; margin: 20px 0; border: 2px solid black;">
    <h3>Alpine Test Component</h3>
    <p>Count: <span x-text="count"></span></p>
    <button @click="increment" style="background: blue; color: white; padding: 10px; cursor: pointer;">
      Click to Test Alpine (Count should increase)
    </button>
  </div>

  <!-- Simple inline Alpine test -->
  <div x-data="{ message: 'Alpine is working!' }" style="background: lightgreen; padding: 10px; margin: 10px 0;">
    <p x-text="message"></p>
    <button @click="message = 'You clicked!'" style="padding: 5px; cursor: pointer;">
      Change Message
    </button>
  </div>

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


  <!-- STATIC HTML FALLBACK (Always visible) -->
  <div style="background: #f0f0f0; padding: 20px; margin: 20px 0; border: 2px solid #333;">
    <h3 style="color: #333;">Static Content (No JS Required)</h3>
    @if($hasValidSections)
      @foreach($sections as $index => $section)
        <div style="margin: 10px 0; padding: 10px; background: white; border: 1px solid #ccc;">
          <strong>{{ $section['title'] }}</strong><br>
          {{ substr($section['description'], 0, 100) }}...
        </div>
      @endforeach
    @else
      <p style="color: red;">NO SECTIONS FOUND!</p>
    @endif
  </div>

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
          <!-- Progress Bar -->
          <div class="absolute left-0 top-0 bottom-0 w-1 {{ $progressTrackClasses }} z-10 rounded-full">
            <div
              class="scroll-progress-bar w-full {{ $progressBarClasses }} transition-all duration-300 ease-out rounded-full"
              style="height: 0%; transform: translateY(0px)"
            ></div>
          </div>

          <!-- Content Container -->
          <div class="scroll-lock-content pl-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center min-h-screen max-w-7xl mx-auto px-4">
              <!-- Left Content -->
              <div class="scroll-content-container relative min-h-[400px]">
                <!-- Content will be populated by JavaScript -->
              </div>

              <!-- Right Image -->
              <div class="relative">
                <div class="sticky top-1/2 transform -translate-y-1/2">
                  <div class="scroll-image-container relative w-full h-96 lg:h-[600px] overflow-hidden rounded-lg shadow-lg">
                    <!-- Images will be populated by JavaScript -->
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Mobile Layout (Stacked) -->
      <div class="block lg:hidden">
        <div class="scroll-mobile-container space-y-12">
          <!-- Mobile content will be populated by JavaScript -->
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

  console.log('Initializing scroll lock component:', blockId);

  // Decode data from base64
  const encodedData = container.dataset.sections;
  const mobileBreakpoint = parseInt(container.dataset.mobileBreakpoint) || 996;

  let sections;
  try {
    sections = JSON.parse(atob(encodedData));
    console.log('Sections loaded:', sections.length);
  } catch (e) {
    console.error('Failed to decode sections data:', e);
    return;
  }

  // DOM elements
  const contentContainer = container.querySelector('.scroll-content-container');
  const imageContainer = container.querySelector('.scroll-image-container');
  const mobileContainer = container.querySelector('.scroll-mobile-container');
  const progressBar = container.querySelector('.scroll-progress-bar');

  let activeSection = 0;
  let isMobile = window.innerWidth <= mobileBreakpoint;

  // Create content elements
  function createContent() {
    // Desktop content
    contentContainer.innerHTML = '';
    imageContainer.innerHTML = '';

    sections.forEach((section, index) => {
      // Create content section
      const contentDiv = document.createElement('div');
      contentDiv.className = `scroll-section absolute w-full transition-opacity duration-700 ease-in-out ${index === 0 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'}`;
      contentDiv.innerHTML = `
        <h3 class="mb-6 {{ $headingClasses }} text-3xl lg:text-4xl font-bold">${section.title}</h3>
        <div class="{{ $textClasses }} text-lg leading-relaxed">${section.description}</div>
      `;
      contentContainer.appendChild(contentDiv);

      // Create image section
      const imageDiv = document.createElement('div');
      imageDiv.className = `absolute inset-0 transition-all duration-700 ease-in-out ${index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-95'}`;
      imageDiv.innerHTML = `
        <img src="${section.image.url}" alt="${section.image.alt || section.title}" class="w-full h-full object-cover">
      `;
      imageContainer.appendChild(imageDiv);
    });

    // Mobile content
    mobileContainer.innerHTML = '';
    sections.forEach((section, index) => {
      const mobileDiv = document.createElement('div');
      mobileDiv.className = 'mobile-section';
      mobileDiv.innerHTML = `
        <div class="mb-6">
          <img src="${section.image.url}" alt="${section.image.alt || section.title}" class="w-full h-auto object-cover rounded-lg">
        </div>
        <div>
          <h3 class="mb-4 {{ $headingClasses }} text-2xl font-bold">${section.title}</h3>
          <div class="{{ $textClasses }} text-base leading-relaxed">${section.description}</div>
        </div>
      `;
      mobileContainer.appendChild(mobileDiv);
    });
  }

  function updateActiveSection(newSection) {
    if (newSection === activeSection) return;

    const contentSections = contentContainer.querySelectorAll('.scroll-section');
    const imageSections = imageContainer.querySelectorAll('div');

    // Hide current
    if (contentSections[activeSection]) {
      contentSections[activeSection].className = contentSections[activeSection].className.replace('opacity-100 pointer-events-auto', 'opacity-0 pointer-events-none');
    }
    if (imageSections[activeSection]) {
      imageSections[activeSection].className = imageSections[activeSection].className.replace('opacity-100 scale-100', 'opacity-0 scale-95');
    }

    // Show new
    activeSection = newSection;
    if (contentSections[activeSection]) {
      contentSections[activeSection].className = contentSections[activeSection].className.replace('opacity-0 pointer-events-none', 'opacity-100 pointer-events-auto');
    }
    if (imageSections[activeSection]) {
      imageSections[activeSection].className = imageSections[activeSection].className.replace('opacity-0 scale-95', 'opacity-100 scale-100');
    }
  }

  function checkMobile() {
    isMobile = window.innerWidth <= mobileBreakpoint;
  }

  function initScrollTrigger() {
    if (isMobile || !window.gsap || !window.ScrollTrigger) {
      console.log('Skipping ScrollTrigger setup');
      return;
    }

    const scrollContent = container.querySelector('.scroll-lock-content');
    if (!scrollContent) return;

    console.log('Setting up ScrollTrigger');

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

        // Update progress bar
        if (progressBar) {
          progressBar.style.height = `${progress * 100}%`;
        }
      }
    });
  }

  // Initialize
  createContent();
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