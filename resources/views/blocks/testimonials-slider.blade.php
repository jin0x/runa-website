@php
  use App\Enums\ContainerSize;
@endphp

@if($testimonials && count($testimonials) > 0)
  <div class="testimonials-slider-block py-16 md:py-24 bg-primary-green-neon">
    <x-container :size="ContainerSize::LARGE">

      <x-slider
        :navigation="$show_navigation"
        :pagination="false"
        :loop="true"
        :autoplayDelay="$autoplay_delay * 1000"
        :slidesPerView="1"
        :mobileSlidesPerView="1"
        :tabletSlidesPerView="2"
        :desktopSlidesPerView="3"
        :spaceBetween="24"
        :navigationPosition="'bottom-right'"
      >
        @foreach($testimonials as $testimonial)
          <div class="swiper-slide">
            <x-testimonial-card
              :post="$testimonial->ID"
              :featured="false"
            />
          </div>
        @endforeach
      </x-slider>

    </x-container>
  </div>
@else
  {{-- No testimonials fallback --}}
  <div class="py-16 md:py-24 bg-primary-green-neon">
    <x-container :size="ContainerSize::LARGE">
      <div class="text-center">
        <p class="text-lg text-white">
          No testimonials available. Create some testimonials to display them here.
        </p>
      </div>
    </x-container>
  </div>
@endif