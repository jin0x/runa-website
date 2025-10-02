@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
@endphp

@props([
    'icon' => null,
    'title' => '',
    'description' => '',
    'pricing_items' => [],
    'cta' => null,
    'features_title' => '',
    'features' => [],
    'asterisk_note' => '',
    'is_popular' => false,
    'class' => '',
])

@php
  // Handle icon URL
  $icon_url = '';
  $icon_alt = '';
  if (!empty($icon) && is_array($icon)) {
      $icon_url = $icon['url'] ?? '';
      $icon_alt = $icon['alt'] ?? $title;
  } elseif (!empty($icon)) {
      $icon_url = $icon;
      $icon_alt = $title;
  }

  // Popular card styling
  $cardClasses = $is_popular 
    ? 'bg-gradient-5 border-2 border-primary-green-soft' 
    : 'bg-neutral-500 border-2 border-transparent';
    
  $buttonVariant = $is_popular ? ButtonVariant::PRIMARY : ButtonVariant::SECONDARY;
  $buttonClasses = $is_popular 
    ? '!bg-primary-green-neon !text-primary-black hover:!bg-primary-green-soft' 
    : '!bg-transparent !text-white !border !border-white hover:!bg-white hover:!text-primary-black';
@endphp


<div class="{{ $cardClasses }} rounded-2xl p-6 relative {{ $class }} bg-neutral-700">
  <div class="flex justify-between items-center mb-6">
    {{-- Icon --}}
    @if($icon_url)
    <div class="">
      <img 
      src="{{ $icon_url }}" 
      alt="{{ $icon_alt }}"
      class="w-12 h-12 object-contain"
      loading="lazy"
      />
    </div>
    @endif

        {{-- Most Popular Badge --}}
    @if($is_popular)
    <div class="">
      <span class="bg-primary-green-neon text-primary-dark text-xs font-medium px-3 py-1 rounded-full">
        MOST POPULAR
      </span>
    </div>
    @endif
  </div>

  {{-- Content Block --}}
  <div class="mb-6">
    {{-- Title --}}
    @if($title)
      <x-heading
        :as="HeadingTag::H4"
        :size="HeadingSize::H4"
        class="text-primary-light mb-3 {{ $is_popular ? 'text-gradient-primary' : '' }}"
      >
        {{ $title }}
      </x-heading>
    @endif

    {{-- Divider --}}
    <div class="h-px bg-neutral-0-32 mb-3"></div>

    {{-- Description --}}
    @if($description)
      <x-text
        :as="TextTag::P"
        :size="TextSize::BASE"
        class="text-neutral-300 mb-3"
      >
        {{ $description }}
      </x-text>
    @endif

    {{-- Pricing Items --}}
    @if(!empty($pricing_items))
      <div class="space-y-1">
        @foreach($pricing_items as $pricing_item)
          <div class="flex items-baseline gap-1">
            @if(!empty($pricing_item['price']))
              <span class="text-primary-green-neon font-bold text-lg">{{ $pricing_item['price'] }}</span>
            @endif
            @if(!empty($pricing_item['label']))
              <span class="text-neutral-300 text-sm">{{ $pricing_item['label'] }}</span>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- CTA Button --}}
  @if($cta && !empty($cta['url']) && !empty($cta['title']))
    <div class="pb-6 max-w-52">
      <x-button
        :variant="$buttonVariant"
        :href="$cta['url']"
        target="{{ $cta['target'] ?? '_self' }}"
        class="{{ $buttonClasses }} w-full justify-center"
      >
        {{ $cta['title'] }}
      </x-button>
    </div>
  @endif

  {{-- Features Section --}}
  @if($features_title || !empty($features))
    <div class="pt-4">
      {{-- Features Title --}}
      @if($features_title)
        <x-text
          :as="TextTag::P"
          :size="TextSize::SMALL"
          class="text-white font-medium mb-3"
        >
          {{ $features_title }}
        </x-text>
      @endif

      {{-- Features List --}}
      @if(!empty($features))
        <ul class="mb-3">
          @foreach($features as $feature)
            <li class="flex items-center gap-2.5 min-h-12">
              {{-- Checkmark Icon --}}
              <div class="">
                <svg width="49" height="48" viewBox="0 0 49 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <mask id="mask0_3073_19444" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="4" y="4" width="41" height="40">
                    <rect x="4.33301" y="4" width="40" height="40" fill="#D9D9D9"/>
                  </mask>
                  <g mask="url(#mask0_3073_19444)">
                    <path d="M18.7222 41.5L15.5555 36.1111L9.36112 34.8055L10.0278 28.6667L6 24L10.0278 19.3611L9.36112 13.2222L15.5555 11.9167L18.7222 6.5L24.3333 9.02779L29.9445 6.5L33.1389 11.9167L39.3055 13.2222L38.6389 19.3611L42.6667 24L38.6389 28.6667L39.3055 34.8055L33.1389 36.1111L29.9445 41.5L24.3333 38.9722L18.7222 41.5ZM19.9445 37.8889L24.3333 36.0278L28.8333 37.8889L31.4722 33.7778L36.25 32.5833L35.7778 27.7222L39.0556 24L35.7778 20.1945L36.25 15.3333L31.4722 14.2222L28.75 10.1111L24.3333 11.9722L19.8333 10.1111L17.1944 14.2222L12.4167 15.3333L12.8889 20.1945L9.61108 24L12.8889 27.7222L12.4167 32.6667L17.1944 33.7778L19.9445 37.8889ZM22.5555 29.6667L32 20.2778L29.9722 18.3333L22.5555 25.6945L18.7222 21.7778L16.6667 23.8055L22.5555 29.6667Z" fill="url(#paint0_linear_3073_19444)"/>
                  </g>
                  <defs>
                    <linearGradient id="paint0_linear_3073_19444" x1="6" y1="41.5" x2="40.9622" y2="4.87297" gradientUnits="userSpaceOnUse">
                      <stop stop-color="#00FFA3"/>
                      <stop offset="0.48313" stop-color="#93FF82"/>
                      <stop offset="0.943979" stop-color="#EEFC51"/>
                    </linearGradient>
                  </defs>
                </svg>
              </div>
              
              {{-- Feature Text --}}
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::SMALL"
                class="text-neutral-300"
              >
                {{ $feature['text'] ?? $feature }}
              </x-text>
            </li>
          @endforeach
        </ul>
      @endif

      {{-- Asterisk Note --}}
      @if($asterisk_note)
        <div class="py-4">
          <x-text
            :as="TextTag::P"
            :size="TextSize::XSMALL"
            class="text-primary-light"
          >
            {{ $asterisk_note }}
          </x-text>
        </div>
      @endif
    </div>
  @endif
</div>