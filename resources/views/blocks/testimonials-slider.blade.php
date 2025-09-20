@php
  use App\Enums\ContainerSize;
  use App\Enums\SectionSize;
@endphp

@if($testimonials && count($testimonials) > 0)
  <x-section :size="SectionSize::LARGE" classes="testimonials-slider-block bg-primary-green-neon {{ $block->classes ?? '' }}">
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
  </x-section>
@else
  {{-- No testimonials fallback --}}
  <x-section :size="SectionSize::LARGE" classes="bg-primary-green-neon {{ $block->classes ?? '' }}">
    <x-container :size="ContainerSize::LARGE">
      <div class="text-center">
        <p class="text-lg text-white">
          No testimonials available. Create some testimonials to display them here.
        </p>
      </div>
    </x-container>
  </x-section>
@endif