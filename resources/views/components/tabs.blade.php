@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'tabs' => [], // Array of tab objects with 'id', 'label', 'content'
    'variant' => 'underline', // 'underline', 'pills', 'buttons'
    'defaultTab' => null, // ID of default active tab (first tab if null)
    'class' => '',
])

@php
  // Validate tabs array
  if (empty($tabs)) {
      return;
  }

  // Set default active tab to first tab if not specified
  $defaultActiveTab = $defaultTab ?? ($tabs[0]['id'] ?? 'tab-1');

  // Define variant classes
  $tabContainerClasses = match($variant) {
      'underline' => 'border-b border-neutral-200',
      'pills' => 'bg-neutral-100 p-1 rounded-lg',
      'buttons' => 'space-x-2',
      default => 'border-b border-neutral-200',
  };

  $tabButtonBaseClasses = match($variant) {
      'underline' => 'px-4 py-2 text-sm font-medium border-b-2 transition-colors duration-200',
      'pills' => 'px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200',
      'buttons' => 'px-4 py-2 text-sm font-medium rounded-md border transition-colors duration-200',
      default => 'px-4 py-2 text-sm font-medium border-b-2 transition-colors duration-200',
  };

  $tabActiveClasses = match($variant) {
      'underline' => 'border-brand-primary text-brand-primary',
      'pills' => 'bg-white text-brand-primary shadow-sm',
      'buttons' => 'bg-brand-primary text-white border-brand-primary',
      default => 'border-brand-primary text-brand-primary',
  };

  $tabInactiveClasses = match($variant) {
      'underline' => 'border-transparent text-neutral-700 hover:text-brand-primary hover:border-neutral-300',
      'pills' => 'text-neutral-700 hover:text-brand-primary',
      'buttons' => 'bg-white text-neutral-700 border-neutral-300 hover:bg-neutral-50',
      default => 'border-transparent text-neutral-700 hover:text-brand-primary hover:border-neutral-300',
  };
@endphp

<div x-data="{ activeTab: '{{ $defaultActiveTab }}' }" class="{{ $class }}">
  {{-- Tab Navigation --}}
  <div class="flex {{ $tabContainerClasses }}">
    @foreach($tabs as $tab)
      <button
        @click="activeTab = '{{ $tab['id'] }}'"
        :class="activeTab === '{{ $tab['id'] }}' ? '{{ $tabActiveClasses }}' : '{{ $tabInactiveClasses }}'"
        class="{{ $tabButtonBaseClasses }}"
        type="button"
      >
        {{ $tab['label'] }}
      </button>
    @endforeach
  </div>

  {{-- Tab Content --}}
  <div class="mt-6">
    @foreach($tabs as $tab)
      <div
        x-show="activeTab === '{{ $tab['id'] }}'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform translate-y-1"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-1"
        id="{{ $tab['id'] }}"
        role="tabpanel"
      >
        @if(isset($tab['content']))
          {!! $tab['content'] !!}
        @endif
      </div>
    @endforeach
  </div>
</div>