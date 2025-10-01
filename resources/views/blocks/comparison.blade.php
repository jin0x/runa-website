@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  <x-container>
    <div class="text-center">
      {{-- Section Heading --}}
    @if($eyebrow)
      <x-text
        :as="TextTag::SPAN"
        :size="TextSize::SMALL"
        :color="TextColor::GRAY"
        class="block mb-3"
      >
        {{ $eyebrow }}
      </x-text>
    @endif

    @if($heading)
      <x-heading
        :as="HeadingTag::H3"
        :size="HeadingSize::H3"
        :color="TextColor::DARK"
        class="mb-3"
      >
        {{ $heading }}
      </x-heading>
    @endif

    @if($subtitle)
      <x-text
        :as="TextTag::P"
        :size="TextSize::MEDIUM"
        :color="TextColor::DARK"
        class="mb-16"
      >
        {{ $subtitle }}
      </x-text>
    @endif

    {{-- Comparison Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-secondary rounded-2xl p-12">
      {{-- Left Side Card --}}
      @if($left_side)
        <div class="text-left">
          {{-- Card Header --}}
          @if($left_side['eyebrow'])
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::XSMALL"
              :color="TextColor::GRAY"
              class="block mb-2 uppercase tracking-wider"
            >
              {{ $left_side['eyebrow'] }}
            </x-text>
          @endif

          @if($left_side['title'])
            <x-heading
              :as="HeadingTag::H2"
              :size="HeadingSize::H2"
              :color="TextColor::DARK"
              class="mb-2"
            >
              {{ $left_side['title'] }}
            </x-heading>
          @endif

          @if($left_side['description'])
            <x-text
              :as="TextTag::P"
              :size="TextSize::BASE"
              :color="TextColor::GRAY"
              class="mb-4"
            >
              {{ $left_side['description'] }}
            </x-text>
          @endif

          {{-- Features List --}}
          @if(!empty($left_side['features']))
            <ul class="space-y-2">
              @foreach($left_side['features'] as $feature)
                <li class="flex items-center gap-2 py-1.5 bg-black/50 rounded-2xl">
                  {{-- Icon --}}
                  <div class="pl-1.5 flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    @if($left_side['icon_type'] === 'checkmark')
                      <svg class="w-5 h-5 text-primary-green-neon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                    @else
                      <svg class="pl-1.5 w-6 h-6 text-secondary-pink" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                      </svg>
                    @endif
                  </div>
                  
                  {{-- Feature Text --}}
                  <x-text
                    :as="TextTag::SPAN"
                    :size="TextSize::LARGE"
                    :color="TextColor::DARK"
                  >
                    {{ $feature['feature_text'] }}
                  </x-text>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      @endif

      {{-- Right Side Card --}}
      @if($right_side)
        <div class="text-left">
          {{-- Card Header --}}
          @if($right_side['eyebrow'])
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::XSMALL"
              :color="TextColor::GRAY"
              class="block mb-2 uppercase tracking-wider"
            >
              {{ $right_side['eyebrow'] }}
            </x-text>
          @endif

          @if($right_side['title'])
            <x-heading
              :as="HeadingTag::H2"
              :size="HeadingSize::H2"
              :color="TextColor::DARK"
              class="mb-2"
            >
              {{ $right_side['title'] }}
            </x-heading>
          @endif

          @if($right_side['description'])
            <x-text
              :as="TextTag::P"
              :size="TextSize::BASE"
              :color="TextColor::GRAY"
              class="mb-4"
            >
              {{ $right_side['description'] }}
            </x-text>
          @endif

          {{-- Features List --}}
          @if(!empty($right_side['features']))
            <ul class="space-y-2">
              @foreach($right_side['features'] as $feature)
                <li class="flex items-center gap-2 py-1.5 bg-black/50 rounded-2xl">
                  {{-- Icon --}}
                  <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    @if($right_side['icon_type'] === 'checkmark')
                      <svg class="pl-1.5 w-5 h-5 text-primary-green-neon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                    @else
                      <svg class="pl-1.5 w-6 h-6 text-secondary-pink" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                      </svg>
                    @endif
                  </div>
                  
                  {{-- Feature Text --}}
                  <x-text
                    :as="TextTag::SPAN"
                    :size="TextSize::LARGE"
                    :color="TextColor::DARK"
                  >
                    {{ $feature['feature_text'] }}
                  </x-text>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      @endif
    </div>
  </div>
  </x-container>
</x-section>