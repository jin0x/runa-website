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
    ? 'bg-gradient-5 border-2 border-primary-green-neon' 
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
    <div class="mb-6 pb-6">
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
    <div class="pt-6">
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
              <div class="flex-shrink-0 w-5 h-5 text-primary-green-neon">
                <svg fill="currentColor" viewBox="0 0 20 20" class="w-full h-full">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
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