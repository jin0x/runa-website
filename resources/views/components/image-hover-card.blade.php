@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Helpers\EnumHelper;
@endphp

@props([
    'title' => '',
    'text' => '',
    'image' => null,
    'cardColor' => 'cyan',
])

@php
  // Get card background class using EnumHelper
  $cardBgClass = EnumHelper::getCardBackgroundClass($cardColor);

  // Generate alt text for image
  $imageAlt = !empty($image['alt']) ? $image['alt'] : $title;
@endphp

<div class="flex flex-col overflow-hidden rounded-3xl group relative">
    @if($image)
        <div class="w-full">
            <img
                src="{{ $image['url'] }}"
                alt="{{ $imageAlt }}"
                class="object-cover h-[380px] md:h-[500px] xl:h-[700px] 2xl:h-full w-full"
            >
        </div>
    @endif

    <div class="flex flex-col flex-1 p-6 xl:p-12 {{ $cardBgClass }}
      relative
      xl:absolute xl:left-0 xl:right-0 xl:w-full xl:bottom-0
      transition-all duration-300 ease-in-out">
        @if($title)
            <x-heading
                :as="HeadingTag::H1"
                :size="HeadingSize::H1"
                class="text-left font-bold text-primary-dark"
            >
                {!! $title !!}
            </x-heading>
        @endif

        @if($text)
            <x-text
                :as="TextTag::P"
                :size="TextSize::XLARGE"
                class="text-left font-normal text-primary-dark mt-3 xl:mt-0
                overflow-hidden xl:max-h-0 xl:opacity-0 transition-all duration-300 ease-in-out group-hover:max-h-[200px] group-hover:opacity-100 group-hover:mt-3"
            >
                {{ $text }}
            </x-text>
        @endif
    </div>
</div>