@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ContainerSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
@endphp

@if($testimonials && count($testimonials) > 0)
  <div class="testimonials-slider-block py-16 md:py-24 bg-neutral-50"
       x-data="testimonialsSlider({{ json_encode([
         'autoplay' => $autoplay,
         'autoplayDelay' => ($autoplay_delay * 1000),
         'showNavigation' => $show_navigation,
         'showPagination' => $show_pagination,
         'totalSlides' => count($testimonials)
       ]) }})"
       x-init="init()">

    <x-container :size="ContainerSize::LARGE">
      {{-- Slider Container --}}
      <div class="relative overflow-hidden">
        <div class="flex transition-transform duration-500 ease-in-out"
             x-bind:style="`transform: translateX(-${currentSlide * 100}%)`">

          @foreach($testimonials as $testimonial)
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

            <div class="w-full flex-shrink-0 px-4">
              <div class="bg-white rounded-2xl border border-neutral-200 p-8 lg:p-12 text-center max-w-4xl mx-auto shadow-sm">

                {{-- Company Logo --}}
                @if($show_company_logos && $companyLogo)
                  <div class="w-20 h-20 mx-auto mb-8 flex items-center justify-center bg-neutral-50 rounded-xl overflow-hidden">
                    <img
                      src="{{ $companyLogo['sizes']['medium'] ?? $companyLogo['url'] }}"
                      alt="{{ $companyName }} logo"
                      class="max-w-full max-h-full object-contain"
                    >
                  </div>
                @endif

                {{-- Rating Stars --}}
                @if($show_ratings && $rating)
                  <div class="flex items-center justify-center gap-1 mb-8">
                    @for($i = 1; $i <= 5; $i++)
                      <svg class="w-5 h-5 {{ $i <= $rating ? 'text-yellow-400' : 'text-neutral-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                      </svg>
                    @endfor
                  </div>
                @endif

                {{-- Quote --}}
                @if($quote)
                  <blockquote class="text-xl lg:text-2xl text-neutral-800 leading-relaxed mb-8 font-light">
                    "{{ $quote }}"
                  </blockquote>
                @endif

                {{-- Client Info --}}
                <div class="space-y-2">
                  @if($clientName)
                    <x-text
                      :as="TextTag::DIV"
                      :size="TextSize::LARGE"
                      class="font-semibold text-neutral-900"
                    >
                      {{ $clientName }}
                    </x-text>
                  @endif

                  <div class="flex flex-col sm:flex-row items-center justify-center gap-2 text-neutral-600">
                    @if($clientPosition)
                      <x-text :as="TextTag::SPAN" :size="TextSize::BASE">
                        {{ $clientPosition }}
                      </x-text>
                    @endif

                    @if($companyName && $clientPosition)
                      <span class="hidden sm:inline">•</span>
                    @endif

                    @if($companyName)
                      @if($websiteUrl)
                        <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" class="text-primary-green-neon hover:text-primary-green-dark transition-colors">
                          {{ $companyName }}
                        </a>
                      @else
                        <x-text :as="TextTag::SPAN" :size="TextSize::BASE">
                          {{ $companyName }}
                        </x-text>
                      @endif
                    @endif
                  </div>

                  @if($testimonialDate)
                    <x-text
                      :as="TextTag::DIV"
                      :size="TextSize::SMALL"
                      class="text-neutral-400 mt-2"
                    >
                      {{ date('F j, Y', strtotime($testimonialDate)) }}
                    </x-text>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Navigation Arrows --}}
      @if($show_navigation && count($testimonials) > 1)
        <div class="flex items-center justify-center gap-4 mt-8">
          <button
            @click="previousSlide()"
            class="flex items-center justify-center w-12 h-12 rounded-full bg-white border border-neutral-200 hover:border-primary-green-neon hover:text-primary-green-neon transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            :disabled="currentSlide === 0 && !infinite"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>

          <button
            @click="nextSlide()"
            class="flex items-center justify-center w-12 h-12 rounded-full bg-white border border-neutral-200 hover:border-primary-green-neon hover:text-primary-green-neon transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            :disabled="currentSlide === totalSlides - 1 && !infinite"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        </div>
      @endif

      {{-- Pagination Dots --}}
      @if($show_pagination && count($testimonials) > 1)
        <div class="flex items-center justify-center gap-2 mt-6">
          @for($i = 0; $i < count($testimonials); $i++)
            <button
              @click="goToSlide({{ $i }})"
              class="w-3 h-3 rounded-full transition-all duration-200"
              :class="currentSlide === {{ $i }} ? 'bg-primary-green-neon' : 'bg-neutral-300 hover:bg-neutral-400'"
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
  <div class="py-16 md:py-24 bg-neutral-50">
    <x-container :size="ContainerSize::LARGE">
      <div class="text-center">
        <x-text
          :as="TextTag::P"
          :size="TextSize::LARGE"
          class="text-neutral-500"
        >
          No testimonials available. Create some testimonials to display them here.
        </x-text>
      </div>
    </x-container>
  </div>
@endif