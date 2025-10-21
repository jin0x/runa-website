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

  // Get the section background color for the SVG fill
  $archColor = match ($variant) {
      ThemeVariant::LIGHT => 'var(--color-primary-light)',
      ThemeVariant::DARK => 'var(--color-primary-dark)',
      ThemeVariant::GREEN => 'var(--color-primary-yellow)',
      ThemeVariant::PURPLE => 'var(--color-secondary-purple)',
      ThemeVariant::CYAN => 'var(--color-secondary-cyan)',
      ThemeVariant::YELLOW => 'var(--color-primary-yellow)',
      default => '#ffffff',
  };

  $variantAttr = match ($variant) {
      ThemeVariant::LIGHT => 'data-variant="light"',
      ThemeVariant::DARK => 'data-variant="dark"',
      ThemeVariant::GREEN => 'data-variant="green"',
      ThemeVariant::PURPLE => 'data-variant="purple"',
      ThemeVariant::CYAN => 'data-variant="cyan"',
      ThemeVariant::YELLOW => 'data-variant="yellow"',
      default => 'data-variant="default"',
  };

  $sectionClasses = "w-full relative $sizeClasses $variantClasses $archClasses $classes";
@endphp

<section class="{{ $sectionClasses }}" {!! $archAttr !!} {!! $variantAttr !!}>
    {{-- Arch SVG - positioned at top of section --}}
  @if($archPosition === ArchPosition::OUTER)
    {{-- Outer arch - concave/inward curve --}}
    <div class="absolute top-0 left-0 w-full h-[72px] lg:h-[72px] md:h-[48px] -translate-y-full pointer-events-none z-10">
      <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 72C0 72 360 0 720 0C1080 0 1440 72 1440 72V72H0V72Z" fill="{{ $archColor }}"/>
      </svg>
    </div>
  @endif

  @if($archPosition === ArchPosition::INNER)
    {{-- Inner arch - convex/outward curve --}}
    <div class="absolute top-0 left-0 w-full h-[72px] lg:h-[72px] md:h-[48px] pointer-events-none z-10">
      <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path class="arch-inner-path" d="M0 0C0 0 360 72 720 72C1080 72 1440 0 1440 0V0H0V0Z" fill="#ffffff"/>
      </svg>
    </div>
  @endif

  {{ $slot }}

</section>
