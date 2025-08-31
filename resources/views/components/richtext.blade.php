@php
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\FontType;
@endphp

@props([
    'as' => TextTag::P, // Default to <p> tag
    'size' => TextSize::BASE, // Default size is text-base
    'font' => FontType::SANS, // Default to sans-serif font
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

  $textClass = trim("{$sizeClasses[$size]} {$fontClasses[$font]} {$class}");
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
