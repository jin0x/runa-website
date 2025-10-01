@php
  use App\Enums\ContainerSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\ThemeVariant;
@endphp

@props([
    'eyebrow' => null,
    'heading' => null,
    'subtitle' => null,
    'variant' => null,
    'classes' => '', // Extra classes for container
    'wrapperClasses' => null, // Classes for optional wrapper div
    'headingClasses' => '', // Extra classes for heading
])

@php
  // Early return if no content to display
  if (empty($eyebrow) && empty($heading) && empty($subtitle)) {
    return;
  }

  // Map eyebrow colors to CSS classes
  $eyebrowClasses = match ($variant) {
      ThemeVariant::LIGHT => 'text-primary-dark',
      ThemeVariant::DARK => 'text-primary-green-soft',
      ThemeVariant::GREEN => 'text-primary-green-neon',
      ThemeVariant::PURPLE => 'text-secondary-purple',
      default => '',
  };

  // Map title colors to CSS classes
  $titleClasses = match ($variant) {
      ThemeVariant::LIGHT => 'text-primary-dark',
      ThemeVariant::DARK => 'text-gradient-primary',
      ThemeVariant::GREEN => 'text-primary-green-neon',
      ThemeVariant::PURPLE => 'text-secondary-purple',
      default => '',
  };

  // Map subtitle colors to CSS classes
  $subtitleClasses = match ($variant) {
      ThemeVariant::LIGHT => 'text-primary-dark',
      ThemeVariant::DARK => 'text-gradient-primary',
      ThemeVariant::GREEN => 'text-primary-green-neon',
      ThemeVariant::PURPLE => 'text-secondary-purple',
      default => '',
  };


  // Append heading classes
  $titleClasses .= ' ' . $headingClasses;
@endphp

@if ($wrapperClasses)
  <div class="{{ $wrapperClasses }}">
@endif

    <x-container :size="ContainerSize::MEDIUM" {{ $attributes->merge(['classes' => 'text-center ' . $classes]) }}>
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::LARGE"
          class="block max-w-max mx-auto pill uppercase mb-8 font-bold {{ $eyebrowClasses }}"
        >
          {!! $eyebrow !!}
        </x-text>

        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H2"
          class="mb-3 {{ $titleClasses }}"
        >
          {!! $heading !!}
        </x-heading>

        <x-text
          :as="TextTag::P"
          :size="TextSize::MEDIUM"
          class="max-w-[68ch] mx-auto {{ $subtitleClasses }}"
        >
          {!! $subtitle !!}
        </x-text>
    </x-container>

@if ($wrapperClasses)
  </div>
@endif
