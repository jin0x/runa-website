@php
  use App\Enums\HeadingSize;
  use App\Enums\HeadingTag;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'items' => [], // Array of accordion items with 'title', 'content', 'id'
    'variant' => 'default', // 'default', 'bordered', 'separated'
    'allowMultiple' => false, // Allow multiple panels open at once
    'defaultOpen' => null, // ID of default open item
    'class' => '',
])

@php
  // Validate items array
  if (empty($items)) {
      return;
  }

  // Generate unique IDs for items that don't have them
  foreach ($items as $index => &$item) {
      if (!isset($item['id'])) {
          $item['id'] = 'accordion-item-' . ($index + 1);
      }
  }

  // Define variant classes
  $containerClasses = match($variant) {
      'bordered' => 'border border-neutral-200 rounded-lg overflow-hidden',
      'separated' => 'space-y-4',
      default => 'space-y-2',
  };

  $itemClasses = match($variant) {
      'bordered' => 'border-b border-neutral-200 last:border-b-0',
      'separated' => 'border border-neutral-200 rounded-lg overflow-hidden',
      default => '',
  };

  $buttonClasses = match($variant) {
      'bordered' => 'w-full px-6 py-4 text-left hover:bg-neutral-50 focus:bg-neutral-50 transition-colors duration-200',
      'separated' => 'w-full px-6 py-4 text-left hover:bg-neutral-50 focus:bg-neutral-50 transition-colors duration-200',
      default => 'w-full py-4 text-left hover:text-brand-primary focus:text-brand-primary transition-colors duration-200',
  };

  $contentClasses = match($variant) {
      'bordered' => 'px-6 pb-4',
      'separated' => 'px-6 pb-4',
      default => 'pb-4',
  };
@endphp

<div 
  x-data="{
    openItems: {{ $allowMultiple ? '[]' : 'null' }},
    defaultOpen: '{{ $defaultOpen }}',
    
    init() {
      if (this.defaultOpen) {
        {{ $allowMultiple ? 'this.openItems.push(this.defaultOpen)' : 'this.openItems = this.defaultOpen' }}
      }
    },
    
    toggle(itemId) {
      if ({{ $allowMultiple ? 'true' : 'false' }}) {
        if (this.openItems.includes(itemId)) {
          this.openItems = this.openItems.filter(id => id !== itemId)
        } else {
          this.openItems.push(itemId)
        }
      } else {
        this.openItems = this.openItems === itemId ? null : itemId
      }
    },
    
    isOpen(itemId) {
      return {{ $allowMultiple ? 'this.openItems.includes(itemId)' : 'this.openItems === itemId' }}
    }
  }"
  class="{{ $containerClasses }} {{ $class }}"
>
  @foreach($items as $item)
    <div class="{{ $itemClasses }}">
      {{-- Accordion Header --}}
      <button
        @click="toggle('{{ $item['id'] }}')"
        :aria-expanded="isOpen('{{ $item['id'] }}')"
        class="{{ $buttonClasses }} flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-opacity-50 rounded"
        type="button"
      >
        <x-heading
          :as="HeadingTag::H3"
          :size="HeadingSize::H5"
          class="text-neutral-900 font-medium"
        >
          {{ $item['title'] }}
        </x-heading>

        {{-- Chevron Icon --}}
        <svg
          :class="isOpen('{{ $item['id'] }}') ? 'rotate-180' : ''"
          class="w-5 h-5 text-neutral-500 transition-transform duration-200"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      {{-- Accordion Content --}}
      <div
        x-show="isOpen('{{ $item['id'] }}')"
        x-collapse
        class="{{ $contentClasses }}"
      >
        <x-text
          :as="TextTag::DIV"
          :size="TextSize::BASE"
          class="text-neutral-700"
        >
          {!! $item['content'] !!}
        </x-text>
      </div>
    </div>
  @endforeach
</div>