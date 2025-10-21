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
      'underline' => 'bg-secondary-purple p-2 px-2 rounded-full',
      'pills' => 'bg-neutral-100 p-1 rounded-lg',
      'buttons' => 'space-x-2',
      default => 'bg-primary-green-neon p-2 rounded-full',
  };

  $tabButtonBaseClasses = match($variant) {
      'underline' => 'px-8 py-6 text-xl rounded-full whitespace-nowrap transition-colors duration-300',
      'pills' => 'px-6 py-2 text-xl font-medium rounded-md whitespace-nowrap transition-colors duration-200',
      'buttons' => 'px-6 py-2 text-xl font-medium rounded-md border whitespace-nowrap transition-colors duration-200',
      default => 'px-6 py-2 text-xl font-medium rounded-full whitespace-nowrap transition-colors duration-300',
  };

  $tabActiveClasses = match($variant) {
      'underline' => 'tab-active-gradient text-primary-dark font-bold',
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

<div
  x-data="tabsComponent('{{ $defaultActiveTab }}')"
  x-init="init()"
  class="{{ $class }}"
>
  {{-- Tab Navigation --}}
  <div class="flex xl:justify-center {{ $tabContainerClasses }}">
    <div class="overflow-x-auto overflow-y-hidden inline-flex xl:flex xl:w-full xl:min-w-0 gap-2 max-h-[88px] items-center rounded-full px-2">
      @foreach($tabs as $tab)
        <button
          @click="switchTab('{{ $tab['id'] }}')"
          :class="activeTab === '{{ $tab['id'] }}' ? '{{ $tabActiveClasses }}' : '{{ $tabInactiveClasses }}'"
          class="{{ $tabButtonBaseClasses }} xl:flex-1 cursor-pointer font-heading"
          type="button"
        >
          {{ $tab['label'] }}
        </button>
      @endforeach
    </div>
  </div>

  {{-- Tab Content with Height Transition --}}
  <div class="mt-16">
    <div
      x-ref="contentWrapper"
      class="relative overflow-hidden transition-all duration-500 ease-in-out"
      :style="'height: ' + contentHeight"
    >
      @foreach($tabs as $tab)
        <div
          x-show="activeTab === '{{ $tab['id'] }}'"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="transition ease-in duration-200"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          data-tab-id="{{ $tab['id'] }}"
          id="{{ $tab['id'] }}"
          role="tabpanel"
          class="w-full"
          :class="activeTab === '{{ $tab['id'] }}' ? 'relative' : 'absolute top-0 left-0 invisible'"
        >
          @if(isset($tab['content']))
            {!! $tab['content'] !!}
          @endif
        </div>
      @endforeach
    </div>
  </div>
</div>

<script>
function tabsComponent(defaultTab) {
  return {
    activeTab: defaultTab,
    contentHeight: 'auto',

    init() {
      this.$nextTick(() => {
        this.updateHeight();
      });
    },

    switchTab(tabId) {
      this.activeTab = tabId;
      this.$nextTick(() => {
        this.updateHeight();
      });
    },

    updateHeight() {
      const activePanel = this.$refs.contentWrapper.querySelector('[data-tab-id="' + this.activeTab + '"]');
      if (activePanel) {
        this.contentHeight = activePanel.offsetHeight + 'px';
      }
    }
  }
}
</script>
