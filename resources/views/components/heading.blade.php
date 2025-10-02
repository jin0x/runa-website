@php
    use App\Enums\FontType;
    use App\Enums\HeadingTag;
    use App\Enums\HeadingSize;
    use App\Enums\TextColor;
@endphp

@props([
    'as' => HeadingTag::H3, // default to h3
    'size' => HeadingSize::H3, // default size
    'font' => null, // optional font
    'color' => null, // optional color override
    'id' => null, // optional id
    'class' => '', // additional classes
])

@php
  // Define size classes for heading (mapped to the abstract classes from Tailwind)
 $sizeClasses = [
        HeadingSize::H1 => 'heading-1',
        HeadingSize::H2 => 'heading-2',
        HeadingSize::H3 => 'heading-3',
        HeadingSize::H4 => 'heading-4',
        HeadingSize::H5 => 'heading-5',
        HeadingSize::H6 => 'heading-6',
        HeadingSize::H7 => 'heading-7',
        HeadingSize::HERO => 'heading-hero',
        HeadingSize::SUPER => 'heading-super',
        HeadingSize::SUPER_DUPER => 'heading-super-duper',
        HeadingSize::DISPLAY_LARGE => 'text-display-large',
        HeadingSize::DISPLAY_MEDIUM => 'text-display-medium',
        HeadingSize::DISPLAY_SMALL => 'text-display-small',
  ];

 // Define optional font classes
    $fontClasses = [
        FontType::SANS => '!font-sans',
        FontType::SERIF => '!font-serif',
        FontType::MONO => '!font-mono',
    ];

    // Define color classes
    $colorClasses = [
        TextColor::LIGHT => 'text-primary-dark',
        TextColor::DARK => 'text-white',
        TextColor::GREEN_SOFT => 'text-primary-green-soft',
        TextColor::GREEN_NEON => 'text-primary-green-neon',
        TextColor::GRADIENT => 'text-gradient-primary',
        TextColor::GRAY => 'text-gray-600',
        TextColor::PINK => 'text-secondary-pink',
        TextColor::CYAN => 'text-secondary-cyan',
    ];

  // Validate that the provided "as" and "size" props are valid
  if (!in_array($as, HeadingTag::getValues())) {
      throw new InvalidArgumentException("Invalid heading tag: {$as}");
  }

  if (!in_array($size, HeadingSize::getValues())) {
      throw new InvalidArgumentException("Invalid heading size: {$size}");
  }

   // If a font is provided, ensure it's valid, otherwise use null (no override)
    if ($font && !in_array($font, FontType::getValues())) {
        throw new InvalidArgumentException("Invalid font type: {$font}");
    }

    // If a color is provided, ensure it's valid
    if ($color && !in_array($color, TextColor::getValues())) {
        throw new InvalidArgumentException("Invalid text color: {$color}");
    }

  // Get the appropriate class for the size prop
  $headingClass = $sizeClasses[$size] ?? $sizeClasses[HeadingSize::H3]; // default to heading-3 size if no size provided

  // If a font is provided, add the font class, otherwise rely on the default in the size class
  $fontClass = $font ? $fontClasses[$font] : '';

  // If a color is provided, add the color class
  $colorClass = $color ? $colorClasses[$color] : '';

  // Combine the heading class with any additional classes passed from the parent
  $classes = trim("{$headingClass} {$fontClass} {$colorClass} {$class}");
@endphp

@if(trim($slot) !== '')
  <{{ $as }} id="{{ $id }}" class="{{ $classes }}">
    {{ $slot }}
  </{{ $as }}>
@endif
