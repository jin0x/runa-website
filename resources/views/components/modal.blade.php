@php
  use App\Enums\HeadingSize;
  use App\Enums\HeadingTag;
@endphp

@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // 'sm', 'md', 'lg', 'xl', 'full'
    'closable' => true,
    'backdrop' => true, // Show backdrop overlay
    'class' => '',
])

@php
  // Define size classes
  $sizeClasses = match($size) {
      'sm' => 'max-w-md',
      'md' => 'max-w-lg',
      'lg' => 'max-w-2xl',
      'xl' => 'max-w-4xl',
      'full' => 'max-w-full mx-4',
      default => 'max-w-lg',
  };

  // Modal wrapper classes
  $modalClasses = "relative w-full {$sizeClasses} max-h-full";
@endphp

<div
  x-data="{ open: false }"
  x-on:open-modal-{{ $id }}.window="open = true"
  x-on:close-modal-{{ $id }}.window="open = false"
  x-on:keydown.escape.window="open && (open = false)"
>
  {{-- Modal Backdrop --}}
  <div
    x-show="open"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none"
    style="display: none;"
  >
    {{-- Backdrop Overlay --}}
    @if($backdrop)
      <div
        @click="open = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
      ></div>
    @endif

    {{-- Modal Content --}}
    <div
      x-show="open"
      x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      class="{{ $modalClasses }} {{ $class }}"
      @click.away="open = false"
    >
      <div class="relative bg-white rounded-lg shadow-xl">
        {{-- Header --}}
        @if($title || $closable)
          <div class="flex items-center justify-between p-6 border-b border-neutral-200">
            @if($title)
              <x-heading
                :as="HeadingTag::H3"
                :size="HeadingSize::H4"
                class="text-neutral-900"
              >
                {{ $title }}
              </x-heading>
            @endif

            @if($closable)
              <button
                @click="open = false"
                type="button"
                class="text-neutral-400 bg-transparent hover:bg-neutral-200 hover:text-neutral-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center transition-colors duration-200"
              >
                <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
              </button>
            @endif
          </div>
        @endif

        {{-- Body --}}
        <div class="p-6">
          {{ $slot }}
        </div>

        {{-- Footer (if needed, can be added via slot) --}}
        @isset($footer)
          <div class="flex items-center justify-end p-6 space-x-3 border-t border-neutral-200">
            {{ $footer }}
          </div>
        @endisset
      </div>
    </div>
  </div>

  {{-- Body scroll lock when modal is open --}}
  <div x-show="open" x-data x-init="document.body.style.overflow = 'hidden'" x-destroy="document.body.style.overflow = 'auto'" style="display: none;"></div>
</div>

{{-- Helper script for opening/closing modals --}}
<script>
  // Helper functions to open/close modals
  window.openModal = function(id) {
    window.dispatchEvent(new CustomEvent('open-modal-' + id));
  }
  
  window.closeModal = function(id) {
    window.dispatchEvent(new CustomEvent('close-modal-' + id));
  }
</script>