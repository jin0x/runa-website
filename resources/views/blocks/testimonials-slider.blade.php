@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ContainerSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;

  // Calculate slides needed (3 testimonials per slide on desktop, 1 on mobile)
  $testimonialsPerSlide = 3;
  $totalSlides = ceil(count($testimonials) / $testimonialsPerSlide);
@endphp

@if($testimonials && count($testimonials) > 0)
  <div class="testimonials-slider-block py-16 md:py-24 bg-primary-green-neon"
       x-data="testimonialsSlider({{ json_encode([
         'autoplay' => $autoplay,
         'autoplayDelay' => ($autoplay_delay * 1000),
         'showNavigation' => $show_navigation,
         'showPagination' => $show_pagination,
         'totalSlides' => $totalSlides,
         'testimonialsPerSlide' => $testimonialsPerSlide
       ]) }})"
       x-init="init()">

    <x-container :size="ContainerSize::LARGE">
      <div class="flex items-start gap-8">
        {{-- Testimonials Grid --}}
        <div class="flex-1 relative overflow-hidden">
          <div class="flex transition-transform duration-500 ease-in-out"
               x-bind:style="`transform: translateX(-${currentSlide * 100}%)`">

            @php
              // Group testimonials into slides
              $chunkedTestimonials = array_chunk($testimonials, $testimonialsPerSlide);
            @endphp

            @foreach($chunkedTestimonials as $slideIndex => $slideTestimonials)
              <div class="w-full flex-shrink-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  @foreach($slideTestimonials as $testimonial)
                    @php
                      // Get ACF fields for this testimonial
                      $companyName = get_field('company_name', $testimonial->ID);
                      $clientName = get_field('client_name', $testimonial->ID);
                      $clientPosition = get_field('client_position', $testimonial->ID);
                      $quote = get_field('quote', $testimonial->ID);
                      $companyLogo = get_field('company_logo', $testimonial->ID);
                      $websiteUrl = get_field('website_url', $testimonial->ID);
                      $rating = get_field('rating', $testimonial->ID);
                      $testimonialDate = get_field('testimonial_date', $testimonial->ID);

                      // Fallback to content if quote is empty
                      if (empty($quote)) {
                        $quote = get_the_content(null, false, $testimonial->ID);
                        $quote = wp_strip_all_tags($quote);
                      }
                    @endphp

                    <div class="bg-white rounded-2xl border border-neutral-200 p-6 text-left shadow-sm h-full flex flex-col">

                      {{-- Company Logo --}}
                      @if($show_company_logos && $companyLogo)
                        <div class="w-16 h-16 mb-4 flex items-center justify-center bg-neutral-50 rounded-xl overflow-hidden">
                          <img
                            src="{{ $companyLogo['sizes']['medium'] ?? $companyLogo['url'] }}"
                            alt="{{ $companyName }} logo"
                            class="max-w-full max-h-full object-contain"
                          >
                        </div>
                      @endif

                      {{-- Rating Stars --}}
                      @if($show_ratings && $rating)
                        <div class="flex items-center gap-1 mb-4">
                          @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-neutral-300' }}" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                          @endfor
                        </div>
                      @endif

                      {{-- Quote --}}
                      @if($quote)
                        <div class="flex-1 mb-4">
                          <blockquote class="text-base text-neutral-800 leading-relaxed">
                            "{{ $quote }}"
                          </blockquote>
                        </div>
                      @endif

                      {{-- Client Info --}}
                      <div class="mt-auto">
                        @if($clientName)
                          <div class="font-semibold text-neutral-900 mb-1">{{ $clientName }}</div>
                        @endif

                        <div class="text-sm text-neutral-600">
                          @if($clientPosition)
                            <div>{{ $clientPosition }}</div>
                          @endif

                          @if($companyName)
                            <div>
                              @if($websiteUrl)
                                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" class="text-primary-green-neon hover:text-primary-green-dark transition-colors">
                                  {{ $companyName }}
                                </a>
                              @else
                                {{ $companyName }}
                              @endif
                            </div>
                          @endif

                          @if($testimonialDate)
                            <div class="text-xs text-neutral-400 mt-1">
                              {{ date('F j, Y', strtotime($testimonialDate)) }}
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Navigation Arrows on the Right --}}
        @if($show_navigation && $totalSlides > 1)
          <div class="flex flex-col gap-4">
            <button
              @click="previousSlide()"
              class="flex items-center justify-center w-12 h-12 rounded-full bg-white border border-neutral-200 hover:border-primary-green-dark hover:text-primary-green-dark transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
              :disabled="currentSlide === 0 && !infinite"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
              </svg>
            </button>

            <button
              @click="nextSlide()"
              class="flex items-center justify-center w-12 h-12 rounded-full bg-white border border-neutral-200 hover:border-primary-green-dark hover:text-primary-green-dark transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
              :disabled="currentSlide === totalSlides - 1 && !infinite"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
        @endif
      </div>

      {{-- Pagination Dots --}}
      @if($show_pagination && $totalSlides > 1)
        <div class="flex items-center justify-center gap-2 mt-8">
          @for($i = 0; $i < $totalSlides; $i++)
            <button
              @click="goToSlide({{ $i }})"
              class="w-3 h-3 rounded-full transition-all duration-200"
              :class="currentSlide === {{ $i }} ? 'bg-white' : 'bg-white/50 hover:bg-white/75'"
            ></button>
          @endfor
        </div>
      @endif
    </x-container>
  </div>

  {{-- Alpine.js Component Script --}}
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('testimonialsSlider', (options = {}) => ({
        currentSlide: 0,
        totalSlides: options.totalSlides || 1,
        testimonialsPerSlide: options.testimonialsPerSlide || 3,
        autoplay: options.autoplay ?? true,
        autoplayDelay: options.autoplayDelay || 5000,
        showNavigation: options.showNavigation ?? true,
        showPagination: options.showPagination ?? true,
        autoplayInterval: null,
        infinite: true, // Enable infinite loop

        init() {
          if (this.autoplay && this.totalSlides > 1) {
            this.startAutoplay();

            // Pause on hover
            this.$el.addEventListener('mouseenter', () => this.stopAutoplay());
            this.$el.addEventListener('mouseleave', () => this.startAutoplay());
          }
        },

        nextSlide() {
          if (this.currentSlide < this.totalSlides - 1) {
            this.currentSlide++;
          } else if (this.infinite) {
            this.currentSlide = 0; // Loop back to first slide
          }
        },

        previousSlide() {
          if (this.currentSlide > 0) {
            this.currentSlide--;
          } else if (this.infinite) {
            this.currentSlide = this.totalSlides - 1; // Loop to last slide
          }
        },

        goToSlide(index) {
          this.currentSlide = index;
        },

        startAutoplay() {
          if (this.autoplay && this.totalSlides > 1) {
            this.autoplayInterval = setInterval(() => {
              this.nextSlide();
            }, this.autoplayDelay);
          }
        },

        stopAutoplay() {
          if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
          }
        }
      }));
    });
  </script>
@else
  {{-- No testimonials fallback --}}
  <div class="py-16 md:py-24 bg-primary-green-neon">
    <x-container :size="ContainerSize::LARGE">
      <div class="text-center">
        <x-text
          :as="TextTag::P"
          :size="TextSize::LARGE"
          class="text-white"
        >
          No testimonials available. Create some testimonials to display them here.
        </x-text>
      </div>
    </x-container>
  </div>
@endif