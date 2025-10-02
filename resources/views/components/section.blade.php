@php
  use App\Enums\SectionSize;
  use App\Enums\ThemeVariant;
@endphp

@props([
    'size' => SectionSize::MEDIUM, // default size is medium
    'variant' => null,
    'classes' => '' // allow additional custom classes
])

@php
  // Define section padding sizes for different screen sizes
  $sizeClasses = match ($size)  {
      SectionSize::NONE => 'px-4 lg:px-8',
      SectionSize::XSMALL => 'px-4 py-4 lg:px-8 lg:py-8',
      SectionSize::SMALL => 'px-4 py-8 lg:px-8 lg:py-12',
      SectionSize::MEDIUM => 'px-4 py-12 lg:px-12 lg:py-24',
      SectionSize::LARGE => 'px-6 py-16 lg:px-16 lg:py-32',
      SectionSize::XLARGE => 'px-8 py-20 lg:px-20 lg:py-40',
      default => 'px-4 py-12 lg:px-12 lg:py-24', // Default fallback in case of unexpected value
  };

  $variantClasses = match ($variant) {
      ThemeVariant::LIGHT => 'bg-white text-primary-dark',
      ThemeVariant::DARK => 'bg-primary-dark text-white',
      ThemeVariant::GREEN => 'bg-gradient-to-b from-primary-yellow to-primary-green-soft text-primary-dark',
      ThemeVariant::PURPLE => 'bg-secondary-purple text-primary-dark',
      ThemeVariant::CYAN => 'bg-secondary-cyan text-primary-dark',
      default => '',
  };

  $sectionClasses = "w-full relative overflow-hidden $sizeClasses $variantClasses $classes";
@endphp

<section class="{{ $sectionClasses }}">
  {{ $slot }}
</section>
