@php
  use App\Enums\ContainerSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\SectionHeadingVariant;
@endphp

@props([
    'eyebrow' => null,
    'heading' => null,
    'subtitle' => null,
    'variant' => null,
    'classes' => '', // Extra classes for container
    'wrapperClasses' => null, // Classes for optional wrapper div
    'headingClasses' => '', // Extra classes for heading
    'isShowcase' => false, // Special styling for showcase block
])

@php
  // Early return if no content to display
  if (empty($eyebrow) && empty($heading) && empty($subtitle)) {
    return;
  }

  // Map eyebrow colors to CSS classes
  $eyebrowClasses = match ($variant) {
      SectionHeadingVariant::LIGHT => 'text-primary-dark',
      SectionHeadingVariant::GREEN => 'text-primary-green-soft',
      SectionHeadingVariant::PURPLE => 'text-secondary-purple',
      SectionHeadingVariant::MIXED_GREEN_LIGHT => 'text-gradient-primary',  // Green eyebrow
      default => '',
  };

  // Map title colors to CSS classes
  $titleClasses = match ($variant) {
      SectionHeadingVariant::LIGHT => 'text-primary-dark',
      SectionHeadingVariant::GREEN => 'text-gradient-primary',
      SectionHeadingVariant::PURPLE => 'text-secondary-purple',
      SectionHeadingVariant::MIXED_GREEN_LIGHT => 'text-white',       // Light title
      default => '',
  };

  // Map subtitle colors to CSS classes
  $subtitleClasses = match ($variant) {
      SectionHeadingVariant::LIGHT => 'text-primary-dark',
      SectionHeadingVariant::GREEN => 'text-gradient-primary',
      SectionHeadingVariant::PURPLE => 'text-secondary-purple',
      SectionHeadingVariant::MIXED_GREEN_LIGHT => 'text-white',       // Light subtitle
      default => '',
  };


  // Append heading classes
  $titleClasses .= ' ' . $headingClasses;
  $headingSize = $isShowcase ? HeadingSize::HERO : HeadingSize::H1;
@endphp

@if ($wrapperClasses)
  <div class="{{ $wrapperClasses }}">
@endif

    <x-container :size="ContainerSize::LARGE" {{ $attributes->merge(['classes' => 'text-center ' . $classes]) }}>
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::EYEBROW"
          class="block max-w-max mx-auto pill uppercase {{ $isShowcase ? 'mb-6' : 'mb-3' }} font-bold {{ $eyebrowClasses }}"
        >
          {!! $eyebrow !!}
        </x-text>

        <x-heading
          :as="HeadingTag::H1"
          :size="$headingSize"
          class="pb-[0.25rem] {{ $isShowcase ? 'mb-6' : 'mb-3' }} font-bold {{ $titleClasses }}"
        >
          {!! $heading !!}
        </x-heading>

        <x-text
          :as="TextTag::P"
          :size="TextSize::XLARGE"
          class="pb-[0.25rem] max-w-[80ch] mx-auto {{ $subtitleClasses }}"
        >
          {!! $subtitle !!}
        </x-text>
    </x-container>

@if ($wrapperClasses)
  </div>
@endif
