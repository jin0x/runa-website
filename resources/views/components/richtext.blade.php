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
    'id' => null, // Optional id
    'class' => '', // Additional custom classes
])

@php
  $sizeClasses = [
      TextSize::CAPTION => 'text-caption',
      TextSize::XSMALL => 'text-xsmall',
      TextSize::SMALL => 'text-small',
      TextSize::BASE => 'text-default',
      TextSize::MEDIUM => 'text-medium',
      TextSize::LARGE => 'text-large',
      TextSize::XLARGE => 'text-xlarge',
  ];

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

  // If a color is provided, ensure it's valid
  if ($color && !in_array($color, TextColor::getValues())) {
      throw new InvalidArgumentException("Invalid text color: {$color}");
  }

  // If a color is provided, add the color class
  $colorClass = $color ? $colorClasses[$color] : '';

  $textClass = trim("{$sizeClasses[$size]} {$fontClasses[$font]} {$colorClass} {$class}");
@endphp

@if(trim(strip_tags($slot)) !== '')
  @foreach(preg_split('/\r\n|\r|\n/', trim(strip_tags($slot))) as $line)
    @if(trim($line) !== '')
      <{{ $as }} id="{{ $id }}" class="{{ $textClass }}">
      {{ $line }}
      </{{ $as }}>
    @endif
  @endforeach
@endif
