@php
  use App\Enums\HeadingSize;
  use App\Enums\HeadingTag;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'items' => [], // Array of timeline items with 'title', 'content', 'date', 'icon'
    'variant' => 'default', // 'default', 'centered', 'minimal'
    'orientation' => 'vertical', // 'vertical', 'horizontal'
    'class' => '',
])

@php
  // Validate items array
  if (empty($items)) {
      return;
  }

  // Define variant classes
  $containerClasses = match($variant) {
      'centered' => $orientation === 'vertical' ? 'relative' : 'flex items-center space-x-8',
      'minimal' => $orientation === 'vertical' ? 'space-y-6' : 'flex items-center space-x-6',
      default => $orientation === 'vertical' ? 'relative' : 'flex items-center space-x-8',
  };

  $lineClasses = match($variant) {
      'centered' => 'absolute left-1/2 transform -translate-x-1/2 w-0.5 bg-neutral-200 h-full',
      'minimal' => '',
      default => $orientation === 'vertical' ? 'absolute left-4 top-0 w-0.5 bg-neutral-200 h-full' : '',
  };
@endphp

<div class="{{ $containerClasses }} {{ $class }}">
  {{-- Timeline Line --}}
  @if($variant !== 'minimal' && $orientation === 'vertical')
    <div class="{{ $lineClasses }}"></div>
  @endif

  @foreach($items as $index => $item)
    @php
      $isLast = $index === count($items) - 1;
      
      $itemClasses = match($variant) {
          'centered' => 'relative flex items-center justify-center mb-8',
          'minimal' => $orientation === 'vertical' ? 'flex items-start space-x-4' : 'flex flex-col items-center text-center',
          default => $orientation === 'vertical' ? 'relative flex items-start space-x-4 mb-8' : 'flex flex-col items-center text-center min-w-0 flex-1',
      };

      $dotClasses = match($variant) {
          'centered' => 'absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-brand-primary rounded-full border-4 border-white shadow-md z-10',
          'minimal' => 'w-3 h-3 bg-brand-primary rounded-full mt-1.5 flex-shrink-0',
          default => 'w-4 h-4 bg-brand-primary rounded-full border-4 border-white shadow-md flex-shrink-0 mt-1 z-10',
      };

      $contentClasses = match($variant) {
          'centered' => $index % 2 === 0 ? 'w-5/12 pr-8 text-right' : 'w-5/12 pl-8 ml-auto',
          'minimal' => 'flex-1',
          default => 'flex-1',
      };
    @endphp

    <div class="{{ $itemClasses }}">
      @if($variant === 'centered')
        {{-- Centered Timeline Layout --}}
        <div class="{{ $contentClasses }}">
          @if($index % 2 === 0)
            {{-- Left Side Content --}}
            @if(isset($item['date']))
              <x-text
                :as="TextTag::TIME"
                :size="TextSize::SMALL"
                class="text-neutral-500 block mb-1"
              >
                {{ $item['date'] }}
              </x-text>
            @endif

            <x-heading
              :as="HeadingTag::H3"
              :size="HeadingSize::H5"
              class="text-neutral-900 mb-2"
            >
              {{ $item['title'] }}
            </x-heading>

            @if(isset($item['content']))
              <x-text
                :as="TextTag::P"
                :size="TextSize::SMALL"
                class="text-neutral-700"
              >
                {!! $item['content'] !!}
              </x-text>
            @endif
          @endif
        </div>

        {{-- Center Dot --}}
        <div class="{{ $dotClasses }}">
          @if(isset($item['icon']))
            {!! $item['icon'] !!}
          @endif
        </div>

        @if($index % 2 !== 0)
          {{-- Right Side Content --}}
          <div class="{{ $contentClasses }}">
            @if(isset($item['date']))
              <x-text
                :as="TextTag::TIME"
                :size="TextSize::SMALL"
                class="text-neutral-500 block mb-1"
              >
                {{ $item['date'] }}
              </x-text>
            @endif

            <x-heading
              :as="HeadingTag::H3"
              :size="HeadingSize::H5"
              class="text-neutral-900 mb-2"
            >
              {{ $item['title'] }}
            </x-heading>

            @if(isset($item['content']))
              <x-text
                :as="TextTag::P"
                :size="TextSize::SMALL"
                class="text-neutral-700"
              >
                {!! $item['content'] !!}
              </x-text>
            @endif
          </div>
        @endif

      @else
        {{-- Default and Minimal Timeline Layout --}}
        <div class="{{ $dotClasses }}">
          @if(isset($item['icon']))
            <div class="w-full h-full flex items-center justify-center text-white text-xs">
              {!! $item['icon'] !!}
            </div>
          @endif
        </div>

        <div class="{{ $contentClasses }}">
          @if(isset($item['date']))
            <x-text
              :as="TextTag::TIME"
              :size="TextSize::XSMALL"
              class="text-neutral-500 block mb-1 {{ $orientation === 'horizontal' ? 'text-center' : '' }}"
            >
              {{ $item['date'] }}
            </x-text>
          @endif

          <x-heading
            :as="HeadingTag::H3"
            :size="HeadingSize::H6"
            class="text-neutral-900 mb-2 {{ $orientation === 'horizontal' ? 'text-center' : '' }}"
          >
            {{ $item['title'] }}
          </x-heading>

          @if(isset($item['content']))
            <x-text
              :as="TextTag::P"
              :size="TextSize::SMALL"
              class="text-neutral-700 {{ $orientation === 'horizontal' ? 'text-center' : '' }}"
            >
              {!! $item['content'] !!}
            </x-text>
          @endif
        </div>
      @endif

      {{-- Horizontal connecting line --}}
      @if($orientation === 'horizontal' && !$isLast && $variant !== 'minimal')
        <div class="flex-1 h-0.5 bg-neutral-200 mx-4"></div>
      @endif
    </div>
  @endforeach
</div>