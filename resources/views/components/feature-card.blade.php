@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Helpers\EnumHelper;
@endphp

@props([
    'image' => null,
    'title' => '',
    'description' => '',
    'cta' => null,
    'cardColor' => 'cyan', // ThemeVariant color
    'size' => 'default', // 'default', 'large', 'small'
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
@endphp

<div class="{{ $cardBgClasses }} {{ $paddingClasses }} rounded-2xl {{ $class }} text-left max-w-md flex flex-col h-full">
  {{-- Image --}}
  @if($image_url)
    <div class="mb-10 overflow-hidden rounded-xl">
      <img 
        src="{{ $image_url }}" 
        alt="{{ $image_alt }}"
        class="w-full {{ $imageHeight }} object-cover"
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
        :size="HeadingSize::H4"
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
    <div class="mt-10">
      <x-button
        :href="$cta['url']"
        target="{{ $cta['target'] ?? '_self' }}"
        class="!text-primary-dark hover:!text-primary-dark underline !p-0 !bg-transparent hover:!bg-transparent"
      >
        {{ $cta['title'] }}
      </x-button>
    </div>
  @endif
</div>