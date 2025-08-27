@php
  use App\Enums\SectionSize;
@endphp

@props([
    'size' => SectionSize::MEDIUM, // default size is medium
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

  $sectionClasses = "w-full relative overflow-hidden $sizeClasses $classes";
@endphp

<section class="{{ $sectionClasses }}">
  {{ $slot }}
</section>
