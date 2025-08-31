@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\HeadingSize;
  use App\Enums\HeadingTag;
@endphp

@props([
    'headers' => [], // Array of header labels
    'rows' => [], // Array of row data arrays
    'sortable' => false, // Enable column sorting
    'searchable' => false, // Enable search
    'pagination' => false, // Enable pagination
    'striped' => false, // Striped rows
    'hoverable' => true, // Hover effect on rows
    'compact' => false, // Compact spacing
    'bordered' => true, // Table borders
    'class' => '',
])

@php
  // Validate data
  if (empty($headers) || empty($rows)) {
      return;
  }

  // Generate unique ID for this table
  $tableId = 'table-' . uniqid();

  // Define table classes
  $tableClasses = 'w-full ' . ($bordered ? 'border border-neutral-200' : '');
  $cellPadding = $compact ? 'px-3 py-2' : 'px-6 py-4';
  $headerClasses = 'bg-neutral-50 border-b border-neutral-200 text-left font-medium text-neutral-900';
  $rowClasses = ($striped ? 'even:bg-neutral-50' : '') . ' ' . ($hoverable ? 'hover:bg-neutral-50' : '') . ' border-b border-neutral-200 last:border-b-0';
@endphp

<div 
  x-data="{
    searchQuery: '',
    sortColumn: null,
    sortDirection: 'asc',
    currentPage: 1,
    itemsPerPage: 10,
    originalRows: {{ json_encode($rows) }},
    
    get filteredRows() {
      let filtered = this.originalRows;
      
      // Search filter
      if (this.searchQuery.trim() !== '') {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(row => 
          row.some(cell => 
            String(cell).toLowerCase().includes(query)
          )
        );
      }
      
      // Sort
      if (this.sortColumn !== null) {
        filtered = [...filtered].sort((a, b) => {
          const aVal = String(a[this.sortColumn]).toLowerCase();
          const bVal = String(b[this.sortColumn]).toLowerCase();
          
          if (this.sortDirection === 'asc') {
            return aVal.localeCompare(bVal);
          } else {
            return bVal.localeCompare(aVal);
          }
        });
      }
      
      return filtered;
    },
    
    get paginatedRows() {
      if (!{{ $pagination ? 'true' : 'false' }}) {
        return this.filteredRows;
      }
      
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredRows.slice(start, end);
    },
    
    get totalPages() {
      return Math.ceil(this.filteredRows.length / this.itemsPerPage);
    },
    
    sort(columnIndex) {
      if (!{{ $sortable ? 'true' : 'false' }}) return;
      
      if (this.sortColumn === columnIndex) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortColumn = columnIndex;
        this.sortDirection = 'asc';
      }
      this.currentPage = 1;
    }
  }"
  class="{{ $class }}"
>
  {{-- Search Bar --}}
  @if($searchable)
    <div class="mb-4">
      <div class="relative">
        <input
          x-model="searchQuery"
          type="text"
          placeholder="Search table..."
          class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent"
        />
        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
          <svg class="w-5 h-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>
    </div>
  @endif

  {{-- Table --}}
  <div class="overflow-x-auto">
    <table class="{{ $tableClasses }}">
      <thead class="{{ $headerClasses }}">
        <tr>
          @foreach($headers as $index => $header)
            <th 
              class="{{ $cellPadding }} {{ $sortable ? 'cursor-pointer select-none hover:bg-neutral-100' : '' }}"
              @if($sortable) @click="sort({{ $index }})" @endif
            >
              <div class="flex items-center justify-between">
                <x-text
                  :as="TextTag::SPAN"
                  :size="TextSize::SMALL"
                  class="font-medium text-neutral-900"
                >
                  {{ $header }}
                </x-text>

                @if($sortable)
                  <div class="flex flex-col ml-2">
                    <svg 
                      :class="sortColumn === {{ $index }} && sortDirection === 'asc' ? 'text-brand-primary' : 'text-neutral-300'"
                      class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg 
                      :class="sortColumn === {{ $index }} && sortDirection === 'desc' ? 'text-brand-primary' : 'text-neutral-300'"
                      class="w-3 h-3 -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                @endif
              </div>
            </th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        <template x-for="(row, rowIndex) in paginatedRows" :key="rowIndex">
          <tr class="{{ $rowClasses }}">
            <template x-for="(cell, cellIndex) in row" :key="cellIndex">
              <td class="{{ $cellPadding }}">
                <x-text
                  :as="TextTag::SPAN"
                  :size="TextSize::SMALL"
                  class="text-neutral-700"
                  x-text="cell"
                ></x-text>
              </td>
            </template>
          </tr>
        </template>
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($pagination)
    <div class="flex items-center justify-between mt-4">
      <div class="text-sm text-neutral-700">
        <span x-text="`Showing ${((currentPage - 1) * itemsPerPage) + 1} to ${Math.min(currentPage * itemsPerPage, filteredRows.length)} of ${filteredRows.length} results`"></span>
      </div>

      <div class="flex space-x-2">
        <button
          @click="currentPage = Math.max(1, currentPage - 1)"
          :disabled="currentPage === 1"
          :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neutral-100'"
          class="px-3 py-2 text-sm border border-neutral-300 rounded-md transition-colors duration-200"
        >
          Previous
        </button>

        <template x-for="page in Array.from({length: totalPages}, (_, i) => i + 1)" :key="page">
          <button
            @click="currentPage = page"
            :class="currentPage === page ? 'bg-brand-primary text-white' : 'hover:bg-neutral-100'"
            class="px-3 py-2 text-sm border border-neutral-300 rounded-md transition-colors duration-200"
            x-text="page"
          ></button>
        </template>

        <button
          @click="currentPage = Math.min(totalPages, currentPage + 1)"
          :disabled="currentPage === totalPages"
          :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-neutral-100'"
          class="px-3 py-2 text-sm border border-neutral-300 rounded-md transition-colors duration-200"
        >
          Next
        </button>
      </div>
    </div>
  @endif

  {{-- Empty State --}}
  <div x-show="filteredRows.length === 0" class="text-center py-8" style="display: none;">
    <x-text
      :as="TextTag::P"
      :size="TextSize::BASE"
      class="text-neutral-500"
    >
      No data found.
    </x-text>
  </div>
</div>