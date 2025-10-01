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
                <li class="flex items-center gap-2 p-1.5 bg-black/50 rounded-2xl">
                  {{-- Icon --}}
                  <div class="flex-shrink-0  w-[26px] h-[26px] flex items-center justify-center">
                    @if($left_side['icon_type'] === 'checkmark')
                      <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="26" height="26" rx="13" fill="url(#paint0_linear_1908_17076)"/>
                        <path d="M11.6666 15.1145L17.7946 8.98584L18.738 9.92851L11.6666 16.9998L7.42395 12.7572L8.36662 11.8145L11.6666 15.1145Z" fill="#151515"/>
                        <defs>
                        <linearGradient id="paint0_linear_1908_17076" x1="0" y1="26" x2="26" y2="0" gradientUnits="userSpaceOnUse">
                          <stop stop-color="#00FFA3"/>
                          <stop offset="0.48313" stop-color="#93FF82"/>
                          <stop offset="0.943979" stop-color="#EEFC51"/>
                        </linearGradient>
                        </defs>
                      </svg>

                    @else
                      <svg class="w-full h-full text-[var(--color-secondary-pink)]" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="26" height="26" rx="13" fill="currentColor"/>
                        <path d="M14.1301 12.687L18.6873 8.12987L17.7444 7.18701L13.1873 11.7442L8.63011 7.18701L7.68726 8.12987L12.2444 12.687L7.68726 17.2442L8.63011 18.187L13.1873 13.6299L17.7444 18.187L18.6873 17.2442L14.1301 12.687Z" fill="black"/>
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
                <li class="flex items-center gap-2 p-1.5 bg-black/50 rounded-2xl">
                  {{-- Icon --}}
                  <div class="flex-shrink-0 w-[26px] h-[26px] flex items-center justify-center">
                    @if($right_side['icon_type'] === 'checkmark')
                      <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="26" height="26" rx="13" fill="url(#paint0_linear_1908_17076)"/>
                        <path d="M11.6666 15.1145L17.7946 8.98584L18.738 9.92851L11.6666 16.9998L7.42395 12.7572L8.36662 11.8145L11.6666 15.1145Z" fill="#151515"/>
                        <defs>
                        <linearGradient id="paint0_linear_1908_17076" x1="0" y1="26" x2="26" y2="0" gradientUnits="userSpaceOnUse">
                          <stop stop-color="#00FFA3"/>
                          <stop offset="0.48313" stop-color="#93FF82"/>
                          <stop offset="0.943979" stop-color="#EEFC51"/>
                        </linearGradient>
                        </defs>
                      </svg>
                    @else
                      <svg class="w-full h-full text-[var(--color-secondary-pink)]" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="26" height="26" rx="13" fill="currentColor"/>
                        <path d="M14.1301 12.687L18.6873 8.12987L17.7444 7.18701L13.1873 11.7442L8.63011 7.18701L7.68726 8.12987L12.2444 12.687L7.68726 17.2442L8.63011 18.187L13.1873 13.6299L17.7444 18.187L18.6873 17.2442L14.1301 12.687Z" fill="black"/>
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