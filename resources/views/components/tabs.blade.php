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
  {{-- Mobile Dropdown Navigation --}}
  <div class="lg:hidden">
    <div class="relative" @click.outside="dropdownOpen = false">
      <button
        @click="dropdownOpen = !dropdownOpen"
        class="w-full bg-secondary-purple text-primary-dark font-heading text-large font-bold px-6 py-4 rounded-lg flex items-center justify-between"
        type="button"
      >
        <span x-text="getCurrentTabLabel()" class="font-normal"></span>
        <svg 
          class="w-5 h-5 transition-transform duration-200"
          :class="dropdownOpen ? 'rotate-180' : ''"
          fill="currentColor" 
          viewBox="0 0 20 20"
        >
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
      
      <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border z-50"
      >
        @foreach($tabs as $tab)
          <button
            @click="switchTab('{{ $tab['id'] }}'); dropdownOpen = false"
            class="w-full text-left px-6 py-4 text-normal text-primary-dark hover:bg-gray-50 first:rounded-t-lg last:rounded-b-lg transition-colors duration-200"
            :class="activeTab === '{{ $tab['id'] }}' ? 'bg-gray-100 font-bold' : ''"
            type="button"
          >
            {{ $tab['label'] }}
          </button>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Desktop Tab Navigation --}}
  <div class="hidden lg:flex lg:justify-center {{ $tabContainerClasses }}">
    <div class="overflow-x-auto overflow-y-hidden inline-flex lg:flex lg:w-full lg:min-w-0 gap-2 max-h-[88px] items-center rounded-full px-2">
      @foreach($tabs as $tab)
        <button
          @click="switchTab('{{ $tab['id'] }}')"
          :class="activeTab === '{{ $tab['id'] }}' ? '{{ $tabActiveClasses }}' : '{{ $tabInactiveClasses }}'"
          class="{{ $tabButtonBaseClasses }} lg:flex-1 cursor-pointer font-heading"
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
    dropdownOpen: false,
    tabs: @json($tabs),

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
    },

    getCurrentTabLabel() {
      const currentTab = this.tabs.find(tab => tab.id === this.activeTab);
      return currentTab ? currentTab.label : '';
    }
  }
}
</script>