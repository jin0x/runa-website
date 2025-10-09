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
            <div class="w-full h-[1px] bg-[linear-gradient(180deg,rgba(0,0,0,004)_0%,rgba(0,0,0,0.10)_100%)] mt-3 mb-3"></div>
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
    </div>
</div>