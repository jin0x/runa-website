@php
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\FontType;
  use App\Enums\TextColor;
@endphp

@props([
    'as' => TextTag::P, // Default to <p> tag
    'size' => TextSize::BASE, // Default size is text-base
    'font' => FontType::SANS, // Default to sans-serif font
    'color' => null, // optional color override
    'id' => null, // optional id
    'class' => '', // Additional custom classes
])

@php
  // Define the size classes for text
  $sizeClasses = [
      TextSize::CAPTION => 'text-caption',
      TextSize::XSMALL => 'text-xsmall',
      TextSize::SMALL => 'text-small',
      TextSize::BASE => 'text-default',
      TextSize::MEDIUM => 'text-medium',
      TextSize::LARGE => 'text-large',
      TextSize::XLARGE => 'text-xlarge',
      TextSize::CAPS => 'text-caps',
      TextSize::EYEBROW => 'text-eyebrow',
  ];

  // Define the font type classes
  $fontClasses = [
      FontType::SANS => 'font-sans',
      FontType::SERIF => 'font-serif',
      FontType::MONO => 'font-mono',
  ];

  // Define color classes
  $colorClasses = [
      TextColor::LIGHT => 'text-primary-dark',
      TextColor::DARK => 'text-white',
      TextColor::GREEN_SOFT => 'text-primary-green-soft',
      TextColor::GREEN_NEON => 'text-primary-green-neon',
      TextColor::GRADIENT => 'text-gradient-primary',
      TextColor::GRAY => 'text-gray-600',
  ];

  // Validate the provided "as", "size", and "font" props
  if (!in_array($as, TextTag::getValues())) {
      throw new InvalidArgumentException("Invalid HTML tag: {$as}");
  }

  if (!in_array($size, TextSize::getValues())) {
      throw new InvalidArgumentException("Invalid text size: {$size}");
  }

  if (!in_array($font, FontType::getValues())) {
      throw new InvalidArgumentException("Invalid font type: {$font}");
  }

  // If a color is provided, ensure it's valid
  if ($color && !in_array($color, TextColor::getValues())) {
      throw new InvalidArgumentException("Invalid text color: {$color}");
  }

  // If a color is provided, add the color class
  $colorClass = $color ? $colorClasses[$color] : '';

  // Get the final class for text size and font type
  $textClass = trim("{$sizeClasses[$size]} {$fontClasses[$font]} {$colorClass} {$class}");
@endphp

@if(trim($slot) !== '')
  <{{ $as }} id="{{ $id }}" class="{{ $textClass }}">
    {{ $slot }}
  </{{ $as }}>
@endif
