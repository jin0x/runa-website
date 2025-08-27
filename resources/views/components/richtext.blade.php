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
      TextSize::XLARGE => 'text-xlarge',
      TextSize::LARGE => 'text-large',
      TextSize::MEDIUM => 'text-medium',
      TextSize::BASE => 'text-default',
      TextSize::SMALL => 'text-small',
      TextSize::XSMALL => 'text-xsmall',
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
