@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextColor;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Set text color based on theme
  $textColor = $themeVariant === ThemeVariant::DARK ? TextColor::LIGHT : TextColor::DARK;

  // Map columns to grid component values
  $gridColumns = match ($columns) {
      '1' => null, // Will use custom wrapper for single column
      '2' => '2',
      '4' => '4',
      default => '3',
  };
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  <x-container classes="flex flex-col items-center">
    {{-- Section Heading --}}
    @if($heading)
      <x-heading
        :as="HeadingTag::H1"
        :size="HeadingSize::H1"
        class="text-center mb-12 max-w-3xl"
      >
        {{ $heading }}
      </x-heading>
    @endif

    {{-- Feature Cards Grid --}}
    @if(!empty($cards))
      @if($columns === '1')
        {{-- Single column layout with centered max-width --}}
        <div class="w-full max-w-md mx-auto">
          @foreach($cards as $card)
            <x-feature-card
              :image="$card['image'] ?? null"
              :title="$card['title'] ?? ''"
              :description="$card['description'] ?? ''"
              :cta="$card['cta'] ?? null"
              :cardColor="$card_color"
              :size="$card_size"
              :imageRatio="$image_ratio ?? null"
            />
          @endforeach
        </div>
      @else
        {{-- Multi-column grid layout --}}
        <x-grid :columns="$gridColumns" gapsize="lg">
          @foreach($cards as $card)
            <x-feature-card
              :image="$card['image'] ?? null"
              :title="$card['title'] ?? ''"
              :description="$card['description'] ?? ''"
              :cta="$card['cta'] ?? null"
              :cardColor="$card_color"
              :size="$card_size"
              :imageRatio="$image_ratio ?? null"
            />
          @endforeach
        </x-grid>
      @endif
    @endif
  </x-container>
</x-section>
