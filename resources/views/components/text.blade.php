@php
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\FontType;
@endphp

@props([
    'as' => TextTag::P, // Default to <p> tag
    'size' => TextSize::BASE, // Default size is text-base
    'font' => FontType::SANS, // Default to sans-serif font
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
      TextSize::CAPS => 'text-small uppercase',
  ];

  // Define the font type classes
  $fontClasses = [
      FontType::SANS => 'font-sans',
      FontType::SERIF => 'font-serif',
      FontType::MONO => 'font-mono',
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

  // Get the final class for text size and font type
  $textClass = trim("{$sizeClasses[$size]} {$fontClasses[$font]} {$class}");
@endphp

@if(trim($slot) !== '')
  <{{ $as }} id="{{ $id }}" class="{{ $textClass }}">
    {{ $slot }}
  </{{ $as }}>
@endif
