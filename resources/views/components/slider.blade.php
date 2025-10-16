{{-- Update slider.blade.php --}}
@props([
    'navigation' => true,
    'pagination' => false,
    'loop' => true,
    'autoplayDelay' => 5000,
    'slidesPerView' => 1,
    'mobileSlidesPerView' => 1,
    'tabletSlidesPerView' => 1,
    'desktopSlidesPerView' => 1,
    'spaceBetween' => 20,
    'navigationPosition' => 'sides', // 'sides' or 'bottom-right'
    'slideCount' => null, // Required: number of slides
])

@php
  // Calculate if we have enough slides for loop mode
  $maxSlidesPerView = max($slidesPerView, $mobileSlidesPerView, $tabletSlidesPerView, $desktopSlidesPerView);
  $hasEnoughSlidesForLoop = $slideCount && $slideCount >= ($maxSlidesPerView + 1);
  
  // Only enable loop if we have enough slides
  $enableLoop = $loop && $hasEnoughSlidesForLoop;
  
  // Only show navigation if we have more than 1 slide
  $showNavigation = $navigation && $slideCount && $slideCount > 1;
@endphp

<div x-data="carouselComponent({
  loop: {{ $enableLoop ? 'true' : 'false' }},
  autoplayDelay: {{ $autoplayDelay }},
  slidesPerView: {{ $slidesPerView }},
  mobileSlidesPerView: {{ $mobileSlidesPerView }},
  tabletSlidesPerView: {{ $tabletSlidesPerView }},
  desktopSlidesPerView: {{ $desktopSlidesPerView }},
  spaceBetween: {{ $spaceBetween }},
  slideCount: {{ $slideCount ?: 0 }}
})" x-init="initSwiper()" class="relative">
  <div class="swiper-container" x-ref="container">
    <div class="swiper-wrapper">
      {!! $slot !!}
    </div>
  </div>

  @if($pagination && $slideCount && $slideCount > 1)
    <div class="swiper-pagination"></div>
  @endif

  @if($showNavigation)
    @if($navigationPosition === 'sides')
      <div class="absolute inset-y-0 left-0 z-10 flex items-center">
        <button @click="swiper && swiper.slidePrev()" class="bg-primary-dark text-dark -ml-2 lg:-ml-4 flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-left w-6 h-6">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>

      <div class="absolute inset-y-0 right-0 z-10 flex items-center">
        <button @click="swiper && swiper.slideNext()" class="bg-primary-dark text-primary-green-soft -mr-2 lg:-mr-4 flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-right w-6 h-6">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    @elseif($navigationPosition === 'bottom-right')
      <div class="flex justify-end gap-2 mt-6">
        <button @click="swiper && swiper.slidePrev()" class="bg-primary-dark text-primary-green-soft flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none hover:bg-neutral-50 transition-colors">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-left w-5 h-5">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <button @click="swiper && swiper.slideNext()" class="bg-primary-dark text-primary-green-soft flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none hover:bg-neutral-50 transition-colors">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-right w-5 h-5">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    @elseif($navigationPosition === 'bottom-center')
      <div class="flex justify-center gap-2 mt-6">
        <button @click="swiper && swiper.slidePrev()" class="bg-primary-dark text-primary-green-soft flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none hover:bg-neutral-50 transition-colors">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-left w-5 h-5">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <button @click="swiper && swiper.slideNext()" class="bg-primary-dark text-primary-green-soft flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none hover:bg-neutral-50 transition-colors">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-right w-5 h-5">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    @endif
  @endif
</div>

<script>
(function() {
  document.addEventListener('alpine:init', () => {
    Alpine.data('carouselComponent', (options = {}) => ({
      swiper: null,
      observer: null,
      hasStarted: false,

      initSwiper() {        
        if (typeof Swiper !== 'undefined') {
          const config = {
            loop: options.loop !== false,
            autoplay: options.autoplayDelay
              ? {
                  delay: options.autoplayDelay,
                  disableOnInteraction: false,
                  pauseOnMouseEnter: true,
                }
              : false,
            slidesPerView: options.slidesPerView || 1,
            spaceBetween: options.spaceBetween || 20,
            watchSlidesProgress: true,
            watchOverflow: true,
            breakpoints: {
              640: { slidesPerView: options.mobileSlidesPerView || 1 },
              768: { slidesPerView: options.tabletSlidesPerView || 1 },
              1024: { slidesPerView: options.desktopSlidesPerView || 1 },
            },
          };

          // Add additional slides for loop mode if needed
          if (config.loop && options.slideCount) {
            config.loopAdditionalSlides = Math.max(2, Math.ceil(options.slidesPerView));
          }

          this.swiper = new Swiper(this.$refs.container, config);

          if (this.swiper.autoplay && config.autoplay) {
            this.setupViewportObserver();
          }
        } else {
          console.error('🎠 Swiper is not defined!');
        }
      },

      setupViewportObserver() {        
        this.observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {              
              if (entry.isIntersecting) {
                if (!this.hasStarted && this.swiper.autoplay) {
                  this.swiper.autoplay.start();
                  this.hasStarted = true;
                }
              } else {
                if (this.swiper.autoplay && this.hasStarted) {
                  this.swiper.autoplay.stop();
                }
              }
            });
          },
          {
            threshold: 0.2,
            rootMargin: '50px',
          }
        );

        if (this.$refs.container) {
          this.observer.observe(this.$refs.container);
        } else {
          console.error('🎠 No container ref!');
        }
      },

      destroy() {
        if (this.observer) {
          this.observer.disconnect();
          this.observer = null;
        }
        if (this.swiper) {
          this.swiper.destroy(true, true);
          this.swiper = null;
        }
      },
    }));
  });
})();
</script>