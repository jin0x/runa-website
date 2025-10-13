@php
  use App\Enums\SectionSize;
  use App\Enums\ThemeVariant;
  use App\Enums\ArchPosition;
@endphp

@props([
    'size' => SectionSize::MEDIUM, // default size is medium
    'variant' => null,
    'archPosition' => ArchPosition::NONE,
    'classes' => '' // allow additional custom classes
])

@php
  // Define section padding sizes for different screen sizes
  $sizeClasses = match ($size)  {
      SectionSize::NONE => '',
      SectionSize::XSMALL => 'py-4 lg:py-8',
      SectionSize::SMALL => 'py-8 lg:py-12',
      SectionSize::MEDIUM => 'py-12 lg:py-16',
      SectionSize::LARGE => 'py-16 lg:py-32',
      SectionSize::XLARGE => 'py-20 lg:py-40',
      default => 'py-12 lg:py-24', // Default fallback in case of unexpected value
  };

  $variantClasses = match ($variant) {
      ThemeVariant::LIGHT => 'bg-white text-primary-dark',
      ThemeVariant::DARK => 'bg-primary-dark text-white',
      ThemeVariant::GREEN => 'bg-gradient-2 text-primary-dark',
      ThemeVariant::PURPLE => 'bg-secondary-purple text-primary-dark',
      ThemeVariant::CYAN => 'bg-secondary-cyan text-primary-dark',
      ThemeVariant::YELLOW => 'bg-primary-yellow text-primary-dark',
      default => '',
  };

  // Add data attribute for arch position (used by CSS)
  $archAttr = match ($archPosition) {
      ArchPosition::OUTER => 'data-arch="outer"',
      ArchPosition::INNER => 'data-arch="inner"',
      default => '',
  };

  // Add arch CSS classes for clip-path and padding compensation
  $archClasses = match ($archPosition) {
      ArchPosition::OUTER => 'arch-outer',
      ArchPosition::INNER => 'arch-inner',
      default => '',
  };

  $sectionClasses = "w-full relative $sizeClasses $variantClasses $archClasses $classes";
@endphp

<section class="{{ $sectionClasses }}" {!! $archAttr !!}>
  {{ $slot }}
</section>
