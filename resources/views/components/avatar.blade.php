@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'src' => '', // Image source
    'alt' => '', // Alt text
    'name' => '', // Name for initials fallback
    'size' => 'md', // 'xs', 'sm', 'md', 'lg', 'xl', '2xl'
    'rounded' => 'full', // 'none', 'sm', 'md', 'lg', 'full'
    'status' => null, // 'online', 'offline', 'away', 'busy'
    'border' => false, // Add border
    'class' => '',
])

@php
  // Define size classes
  $sizeClasses = match($size) {
      'xs' => 'w-6 h-6',
      'sm' => 'w-8 h-8',
      'lg' => 'w-12 h-12',
      'xl' => 'w-16 h-16',
      '2xl' => 'w-20 h-20',
      default => 'w-10 h-10',
  };

  // Define text size for initials
  $textSize = match($size) {
      'xs' => TextSize::XSMALL,
      'sm' => TextSize::SMALL,
      'lg' => TextSize::MEDIUM,
      'xl' => TextSize::LARGE,
      '2xl' => TextSize::XLARGE,
      default => TextSize::BASE,
  };

  // Define rounded classes
  $roundedClasses = match($rounded) {
      'none' => 'rounded-none',
      'sm' => 'rounded-sm',
      'md' => 'rounded-md',
      'lg' => 'rounded-lg',
      default => 'rounded-full',
  };

  // Define status dot size
  $statusSize = match($size) {
      'xs' => 'w-2 h-2',
      'sm' => 'w-2.5 h-2.5',
      'lg' => 'w-3.5 h-3.5',
      'xl' => 'w-4 h-4',
      '2xl' => 'w-5 h-5',
      default => 'w-3 h-3',
  };

  // Define status colors
  $statusClasses = match($status) {
      'online' => 'bg-semantic-success',
      'offline' => 'bg-neutral-400',
      'away' => 'bg-semantic-warning',
      'busy' => 'bg-semantic-error',
      default => '',
  };

  // Generate initials from name
  $initials = '';
  if ($name) {
      $words = explode(' ', trim($name));
      $initials = strtoupper(substr($words[0], 0, 1));
      if (count($words) > 1) {
          $initials .= strtoupper(substr(end($words), 0, 1));
      }
  }

  // Border classes
  $borderClasses = $border ? 'ring-2 ring-white shadow-md' : '';
@endphp

<div class="relative inline-block {{ $class }}">
  <div class="{{ $sizeClasses }} {{ $roundedClasses }} {{ $borderClasses }} overflow-hidden bg-neutral-200 flex items-center justify-center">
    @if($src)
      {{-- Image Avatar --}}
      <img
        src="{{ $src }}"
        alt="{{ $alt ?: $name }}"
        class="w-full h-full object-cover"
      />
    @elseif($initials)
      {{-- Initials Avatar --}}
      <x-text
        :as="TextTag::SPAN"
        :size="$textSize"
        class="text-neutral-700 font-medium"
      >
        {{ $initials }}
      </x-text>
    @else
      {{-- Default Avatar Icon --}}
      <svg class="w-1/2 h-1/2 text-neutral-400" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
      </svg>
    @endif
  </div>

  {{-- Status Indicator --}}
  @if($status)
    <span class="absolute bottom-0 right-0 {{ $statusSize }} {{ $statusClasses }} border-2 border-white rounded-full"></span>
  @endif
</div>