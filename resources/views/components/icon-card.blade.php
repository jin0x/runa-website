@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;
@endphp

@props([
    'icon' => null,
    'title' => '',
    'text' => '',
    'cardColor' => 'cyan',
    'textColor' => TextColor::LIGHT,
    'class' => '',
    'cta' => null,
])

@php
  // Get background class using EnumHelper
  $backgroundClass = EnumHelper::getCardBackgroundClass($cardColor);

  // Handle icon data
  $iconUrl = '';
  $iconAlt = '';
  if (!empty($icon) && is_array($icon)) {
      $iconUrl = $icon['url'] ?? '';
      $iconAlt = !empty($icon['alt']) ? $icon['alt'] : $title;
  }
@endphp

<div class="flex flex-col overflow-hidden gap-y-10 p-6 rounded-xl {{ $backgroundClass }} {{ $class }}">
    @if($iconUrl)
        <div class="w-full overflow-hidden">
            <img
                src="{{ $iconUrl }}"
                alt="{{ $iconAlt }}"
                class="object-contain h-12 w-12"
            >
        </div>
    @endif

    <div class="flex flex-col flex-1 gap-y-3 min-h-[175px]">
        @if($title)
            <x-heading
                id="main-title"
                :as="HeadingTag::H4"
                :size="HeadingSize::H4"
                :color="$textColor"
                class="text-left font-extrabold"
            >
                {{ $title }}
            </x-heading>

            {{-- Divider --}}
            <div class="w-full h-[1px] bg-[linear-gradient(180deg,rgba(0,0,0,0.04)_0%,rgba(0,0,0,0.10)_100%)] mt-3 mb-3"></div>
        @endif

        @if($text)
            <x-text
                :as="TextTag::P"
                :size="TextSize::SMALL"
                :color="$textColor"
                class="text-left font-normal text-default"
            >
                {{ $text }}
            </x-text>
        @endif
        {{-- CTA --}}
        @if($cta && !empty($cta['url']) && !empty($cta['title']))
            <div class="mt-auto">
            <x-text
                href="{{ $cta['url'] }}"
                target="{{ $cta['target'] ?? '_self' }}"
                :as="TextTag::A" 
                :size="TextSize::XSMALL" 
                :color="TextColor::LIGHT"
                class="inline-flex items-center gap-2 !no-underline hover:underline transition-all duration-200 ease-in-out"
            >
                <span>{{ $cta['title'] }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 14 14" fill="none">
                <path d="M7.00016 0.663574L5.82516 1.83857L10.4752 6.49691H0.333496V8.16357H10.4752L5.82516 12.8219L7.00016 13.9969L13.6668 7.33024L7.00016 0.663574Z" fill="black"/>
                </svg>
            </x-text>
            </div>
        @endif
    </div>
</div>