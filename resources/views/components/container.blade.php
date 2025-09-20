@php
  use \App\Enums\ContainerSize;
@endphp

@props([
    'size' => ContainerSize::XLARGE, // Default size
    'classes' => '', // Extra classes
])

@php
  // Map sizes to TailwindCSS classes
  $sizeClasses = match ($size) {
      ContainerSize::XSMALL => 'lg:max-w-2xl',
      ContainerSize::SMALL => 'lg:max-w-4xl',
      ContainerSize::MEDIUM => 'lg:max-w-6xl',
      ContainerSize::LARGE => 'lg:max-w-7xl',
      ContainerSize::XLARGE => 'lg:max-w-8xl',
      ContainerSize::WIDE => 'lg:max-w-9xl',
      ContainerSize::FULL => 'lg:max-w-full',
      default => '',
  };

  // Base container classes
  $containerClasses = "w-full mx-auto relative px-6 lg:px-8 $sizeClasses $classes";
@endphp

<div class="{{ $containerClasses }}">
  {{ $slot }}
</div>

