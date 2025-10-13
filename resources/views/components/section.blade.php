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

  // Get background color for arch SVG
  $archColor = match ($variant) {
      ThemeVariant::LIGHT => '#ffffff',
      ThemeVariant::DARK => '#000000', // Adjust to your primary-dark color
      ThemeVariant::GREEN => '#00ffa3', // Adjust to your gradient-2 color
      ThemeVariant::PURPLE => '#af44ec', // Adjust to your secondary-purple color
      ThemeVariant::CYAN => '#0ce3f8', // Adjust to your secondary-cyan color
      ThemeVariant::YELLOW => '#eefc51', // Adjust to your primary-yellow color
      default => '#ffffff',
  };

  // Add extra spacing when arch is present
  $archSpacing = match ($archPosition) {
      ArchPosition::TOP => '!after:pt-[72px]',
      ArchPosition::BOTTOM => '!after:pb-[72px]',
      default => '',
  };

  $isOverflowVisible = $archPosition === ArchPosition::NONE ? '' : 'overflow-visible';

  $sectionClasses = "w-full relative $isOverflowVisible $sizeClasses $variantClasses $archSpacing $classes";

@endphp

<section class="{{ $sectionClasses }}">
    {{-- Top Arch --}}
  @if($archPosition === ArchPosition::TOP)
    <div class="absolute top-0 left-0 w-full h-[72px] -translate-y-full pointer-events-none">
      <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 72C0 72 360 0 720 0C1080 0 1440 72 1440 72V72H0V72Z" fill="{{ $archColor }}"/>
      </svg>
    </div>
  @endif

  {{ $slot }}

  {{-- Bottom Arch --}}
  @if($archPosition === ArchPosition::BOTTOM)
    <div class="absolute bottom-0 left-0 w-full h-[72px] translate-y-full pointer-events-none z-10">
      <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 72" fill="none" xmlns="http://www.w3.org/2000/svg">
       <path d="M0 0C0 0 360 72 720 72C1080 72 1440 0 1440 0V0H0V0Z" fill="{{ $archColor }}"/>
      </svg>
    </div>
  @endif
</section>
