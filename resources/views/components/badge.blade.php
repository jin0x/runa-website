@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'variant' => 'default', // 'default', 'primary', 'secondary', 'success', 'warning', 'error', 'info'
    'size' => 'md', // 'sm', 'md', 'lg'
    'rounded' => 'md', // 'none', 'sm', 'md', 'lg', 'full'
    'removable' => false, // Show remove button
    'dot' => false, // Show status dot
    'class' => '',
])

@php
  // Define variant classes
  $variantClasses = match($variant) {
      'primary' => 'bg-brand-primary text-white',
      'secondary' => 'bg-brand-secondary text-white',
      'success' => 'bg-semantic-success text-white',
      'warning' => 'bg-semantic-warning text-neutral-900',
      'error' => 'bg-semantic-error text-white',
      'info' => 'bg-semantic-info text-neutral-900',
      default => 'bg-neutral-100 text-neutral-700',
  };

  // Define size classes
  $sizeClasses = match($size) {
      'sm' => 'px-2 py-0.5 text-xs',
      'lg' => 'px-4 py-2 text-sm',
      default => 'px-3 py-1 text-sm',
  };

  // Define rounded classes
  $roundedClasses = match($rounded) {
      'none' => 'rounded-none',
      'sm' => 'rounded-sm',
      'lg' => 'rounded-lg',
      'full' => 'rounded-full',
      default => 'rounded-md',
  };

  // Define dot color based on variant
  $dotClasses = match($variant) {
      'primary' => 'bg-brand-primary',
      'secondary' => 'bg-brand-secondary',
      'success' => 'bg-semantic-success',
      'warning' => 'bg-semantic-warning',
      'error' => 'bg-semantic-error',
      'info' => 'bg-semantic-info',
      default => 'bg-neutral-400',
  };
@endphp

<span class="inline-flex items-center gap-1.5 font-medium {{ $variantClasses }} {{ $sizeClasses }} {{ $roundedClasses }} {{ $class }}">
  {{-- Status Dot --}}
  @if($dot)
    <span class="w-2 h-2 rounded-full {{ $dotClasses }}"></span>
  @endif

  {{-- Badge Content --}}
  <span>{{ $slot }}</span>

  {{-- Remove Button --}}
  @if($removable)
    <button
      type="button"
      class="inline-flex items-center justify-center w-4 h-4 ml-1 text-current hover:bg-black/10 rounded-full transition-colors duration-200"
      onclick="this.closest('.badge').remove()"
    >
      <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
      </svg>
      <span class="sr-only">Remove badge</span>
    </button>
  @endif
</span>