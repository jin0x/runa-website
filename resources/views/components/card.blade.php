@php
  use App\Enums\FontType;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
@endphp

@props([
    'title' => '',                  // Card title
    'eyebrow' => '',               // Eyebrow text (category)
    'excerpt' => '',               // Card excerpt/description
    'image' => '',                 // Image URL
    'cta' => [                     // CTA attributes
        'label' => 'Read more',
        'url' => '#',
        'target' => '_self'
    ],
    'headingTag' => HeadingTag::H3,  // Heading tag (h1-h6)
    'headingSize' => HeadingSize::H4, // Heading size
    'headingFont' => FontType::SANS,  // Heading font
    'class' => '',                  // Additional classes for the card
])

@php
  // Make sure cta has all required properties
  $cta = array_merge([
      'label' => 'Read more',
      'url' => '#',
      'target' => '_self'
  ], $cta);
@endphp

<div class="flex flex-col rounded-[32px] overflow-hidden relative {{ $class }}">
  <a href="{{ $cta['url'] }}"
     class="hidden lg:block absolute top-0 left-0 w-full h-full">
  </a>
  <div>
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover min-h-[328px] max-h-[328px]"/>
  </div>
  <div class="bg-white text-primary-navy flex flex-col flex-1 p-6 lg:p-10 2xl:p-16 min-h-[350px] 2xl:min-h-[410px] rounded-bl-[32px] rounded-br-[32px] overflow-hidden">
    <div class="mb-auto space-y-4">
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::XSMALL"
          class="pill pill-purple"
        >
          {!! $eyebrow !!}
        </x-text>

      <x-heading
        id="main-title"
        :as="$headingTag"
        :size="$headingSize"
        :font="$headingFont"
        class="text-primary-purple"
      >
        {{ $title }}
      </x-heading>

      @if(!empty($excerpt))
        <x-text
          :as="TextTag::P"
          :size="TextSize::SMALL"
        >
          {{ $excerpt }}
        </x-text>
      @endif
    </div>
    <div class="pt-12 lg:pt-16">
      <a href="{{ $cta['url'] }}" class="text-primary-navy hover:text-primary-orange text-sm font-normal mt-4 block underline underline-offset-4" target="{{ $cta['target'] }}">{{ $cta['label'] }}</a>
    </div>
  </div>
</div>
