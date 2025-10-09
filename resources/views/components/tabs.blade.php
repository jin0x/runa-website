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
      'underline' => 'bg-secondary-purple p-2 ',
      'pills' => 'bg-neutral-100 p-1 rounded-lg',
      'buttons' => 'space-x-2',
      default => 'bg-primary-green-neon p-2 rounded-full',
  };

  $tabButtonBaseClasses = match($variant) {
      'underline' => 'px-12 py-6 text-xl rounded-full whitespace-nowrap transition-all duration-300',
      'pills' => 'px-6 py-2 text-xl font-medium rounded-md whitespace-nowrap',
      'buttons' => 'px-6 py-2 text-xl font-medium rounded-md border whitespace-nowrap',
      default => 'px-8 py-2 text-xl font-medium rounded-full whitespace-nowrap transition-all duration-300',
  };

  $tabActiveClasses = match($variant) {
      'underline' => 'tab-active-gradient font-bold text-primary-dark',
      'pills' => 'bg-white text-brand-primary shadow-sm',
      'buttons' => 'bg-brand-primary text-white border-brand-primary',
      default => 'bg-white text-primary-dark',
  };

  $tabInactiveClasses = match($variant) {
      'underline' => 'bg-transparent font-regular text-primary-dark hover:bg-white/20',
      'pills' => 'text-neutral-700 hover:text-brand-primary',
      'buttons' => 'bg-white text-neutral-700 border-neutral-300 hover:bg-neutral-50',
      default => 'bg-transparent text-primary-dark hover:bg-white/20',
  };
@endphp

<div x-data="{ activeTab: '{{ $defaultActiveTab }}' }" class="{{ $class }}">
  {{-- Tab Navigation --}}
  <div class="overflow-x-auto flex xl:justify-center rounded-full scrollbar-hide">
    <div class="inline-flex xl:flex xl:w-full xl:min-w-0 {{ $tabContainerClasses }} gap-2 min-w-max">
      @foreach($tabs as $tab)
        <button
          @click="activeTab = '{{ $tab['id'] }}'"
          :class="activeTab === '{{ $tab['id'] }}' ? '{{ $tabActiveClasses }}' : '{{ $tabInactiveClasses }}'"
          class="{{ $tabButtonBaseClasses }} xl:flex-1"
          type="button"
        >
          {{ $tab['label'] }}
        </button>
      @endforeach
    </div>
  </div>

  {{-- Tab Content --}}
  <div class="mt-16">
    @foreach($tabs as $tab)
      <div
        x-show="activeTab === '{{ $tab['id'] }}'"
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