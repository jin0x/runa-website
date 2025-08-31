@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'content' => '', // Tooltip content
    'position' => 'top', // 'top', 'bottom', 'left', 'right'
    'trigger' => 'hover', // 'hover', 'click'
    'delay' => 0, // Delay in milliseconds
    'class' => '',
])

@php
  // Define position classes
  $positionClasses = match($position) {
      'top' => 'bottom-full left-1/2 transform -translate-x-1/2 mb-2',
      'bottom' => 'top-full left-1/2 transform -translate-x-1/2 mt-2',
      'left' => 'right-full top-1/2 transform -translate-y-1/2 mr-2',
      'right' => 'left-full top-1/2 transform -translate-y-1/2 ml-2',
      default => 'bottom-full left-1/2 transform -translate-x-1/2 mb-2',
  };

  // Define arrow classes
  $arrowClasses = match($position) {
      'top' => 'top-full left-1/2 transform -translate-x-1/2 border-t-neutral-900 border-t-8 border-x-transparent border-x-8 border-b-0',
      'bottom' => 'bottom-full left-1/2 transform -translate-x-1/2 border-b-neutral-900 border-b-8 border-x-transparent border-x-8 border-t-0',
      'left' => 'left-full top-1/2 transform -translate-y-1/2 border-l-neutral-900 border-l-8 border-y-transparent border-y-8 border-r-0',
      'right' => 'right-full top-1/2 transform -translate-y-1/2 border-r-neutral-900 border-r-8 border-y-transparent border-y-8 border-l-0',
      default => 'top-full left-1/2 transform -translate-x-1/2 border-t-neutral-900 border-t-8 border-x-transparent border-x-8 border-b-0',
  };

  // Define trigger events
  if ($trigger === 'hover') {
      $showEvent = '@mouseenter';
      $hideEvent = '@mouseleave';
  } else {
      $showEvent = '@click';
      $hideEvent = '@click.away';
  }
@endphp

<div 
  x-data="{ 
    show: false,
    timeout: null,
    
    showTooltip() {
      if ({{ $delay }}) {
        this.timeout = setTimeout(() => {
          this.show = true
        }, {{ $delay }})
      } else {
        this.show = true
      }
    },
    
    hideTooltip() {
      if (this.timeout) {
        clearTimeout(this.timeout)
      }
      this.show = false
    }
  }"
  class="relative inline-block {{ $class }}"
  {{ $showEvent }}="showTooltip()"
  {{ $hideEvent }}="hideTooltip()"
>
  {{-- Trigger Element --}}
  {{ $slot }}

  {{-- Tooltip --}}
  <div
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="absolute z-50 {{ $positionClasses }}"
    style="display: none;"
  >
    {{-- Tooltip Content --}}
    <div class="bg-neutral-900 text-white text-sm rounded-lg py-2 px-3 max-w-xs shadow-lg">
      <x-text
        :as="TextTag::SPAN"
        :size="TextSize::SMALL"
        class="text-white"
      >
        {!! $content !!}
      </x-text>
    </div>

    {{-- Arrow --}}
    <div class="absolute w-0 h-0 {{ $arrowClasses }}"></div>
  </div>
</div>