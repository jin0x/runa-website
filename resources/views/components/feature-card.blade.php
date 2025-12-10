@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;
@endphp

@props([
    'image' => null,
    'title' => '',
    'description' => '',
    'cta' => null,
    'cardColor' => 'cyan', // ThemeVariant color
    'size' => 'default', // 'default', 'large', 'small'
    'imageRatio' => null, // e.g., '16:9' to override height with aspect ratio
    'class' => '',
])

@php
  // Handle image URL
  $image_url = '';
  $image_alt = '';
  if (!empty($image) && is_array($image)) {
      $image_url = $image['url'] ?? '';
      $image_alt = $image['alt'] ?? $title;
  } elseif (!empty($image)) {
      $image_url = $image;
      $image_alt = $title;
  }

  // Use EnumHelper for consistent card background colors
  $cardBgClasses = EnumHelper::getCardBackgroundClass($cardColor);

  $textColor = 'text-primary-dark'; // Consistent dark text on bright backgrounds

  // Size-based styling
  $paddingClasses = match ($size) {
      'large' => 'p-8',
      'small' => 'p-4',
      default => 'p-6',
  };

  $imageHeight = match ($size) {
      'large' => 'h-96',
      'small' => 'h-48',
      default => 'h-[372px]',
  };

  $imageClass = ($imageRatio === '16:9' || $imageRatio === '16/9')
      ? 'w-full aspect-[16/9] object-cover'
      : "w-full {$imageHeight} object-cover";
@endphp

<div class="{{ $cardBgClasses }} {{ $paddingClasses }} rounded-2xl {{ $class }} text-left max-w-md flex flex-col h-full">
  {{-- Image --}}
  @if($image_url)
    <div class="mb-10 overflow-hidden rounded-xl">
      <img 
        src="{{ $image_url }}" 
        alt="{{ $image_alt }}"
        class="{{ $imageClass }}"
        loading="lazy"
      />
    </div>
  @endif

  {{-- Content --}}
  <div class="bg-transparent flex-grow">
    {{-- Title --}}
    @if($title)
      <x-heading
        :as="HeadingTag::H4"
        :size="HeadingSize::H4_BOLD"
        class="{{ $textColor }} mb-3"
      >
        {{ $title }}
      </x-heading>
    @endif

    {{-- Description (Optional) --}}
    @if($description)
      <x-text
        :as="TextTag::P"
        :size="TextSize::BASE"
        class="{{ $textColor }}"
      >
        {{ $description }}
      </x-text>
    @endif
  </div>

  {{-- CTA --}}
  @if($cta && !empty($cta['url']) && !empty($cta['title']))
    <div class="mt-3">
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