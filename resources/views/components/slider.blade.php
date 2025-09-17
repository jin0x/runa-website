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
])

<div x-data="carouselComponent()" x-init="initSwiper()" class="relative">
  <div class="swiper-container" x-ref="container">
    <div class="swiper-wrapper">
      {!! $slot !!}
    </div>
  </div>

  @if($pagination)
    <div class="swiper-pagination"></div>
  @endif

  @if($navigation)
    @if($navigationPosition === 'sides')
      <div class="absolute inset-y-0 left-0 z-10 flex items-center">
        <button @click="swiper && swiper.slidePrev()" class="bg-white text-dark -ml-2 lg:-ml-4 flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-left w-6 h-6">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>

      <div class="absolute inset-y-0 right-0 z-10 flex items-center">
        <button @click="swiper && swiper.slideNext()" class="bg-white text-dark -mr-2 lg:-mr-4 flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-right w-6 h-6">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    @elseif($navigationPosition === 'bottom-right')
      <div class="flex justify-end gap-2 mt-6">
        <button @click="swiper && swiper.slidePrev()" class="bg-white text-dark flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none hover:bg-neutral-50 transition-colors">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-left w-5 h-5">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <button @click="swiper && swiper.slideNext()" class="bg-white text-dark flex justify-center items-center w-10 h-10 rounded-full shadow focus:outline-none hover:bg-neutral-50 transition-colors">
          <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-right w-5 h-5">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    @endif
  @endif
</div>

<script>
  function carouselComponent() {
    return {
      swiper: null,
      initSwiper() {
        if ( typeof Swiper !== 'undefined' ) {
          this.swiper = new Swiper( this.$refs.container, {
            @if($loop)
            loop: true,
            @endif
            autoplay: {
              delay: {{ $autoplayDelay }},
              disableOnInteraction: false,
            },
            slidesPerView: {{ $slidesPerView }},
            spaceBetween: {{ $spaceBetween }},
            breakpoints: {
              640: { slidesPerView: {{ $mobileSlidesPerView }} },
              768: { slidesPerView: {{ $tabletSlidesPerView }} },
              1024: { slidesPerView: {{ $desktopSlidesPerView }} },
            },
          } );

          if ( this.swiper.autoplay ) {
            console.log("▶️");
            this.swiper.autoplay.start();
          }

        } else {
          console.error( 'Swiper library not loaded.' );
        }
      }
    }
  }
</script>
