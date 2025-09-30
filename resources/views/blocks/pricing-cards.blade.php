@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\ThemeVariant;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = match ($section_size) {
      'none' => SectionSize::NONE,
      'xs' => SectionSize::XSMALL,
      'sm' => SectionSize::SMALL,
      'md' => SectionSize::MEDIUM,
      'lg' => SectionSize::LARGE,
      'xl' => SectionSize::XLARGE,
      default => SectionSize::MEDIUM,
  };

  // Convert theme string to ThemeVariant enum
  $themeVariant = match ($theme) {
      'light' => ThemeVariant::LIGHT,
      'dark' => ThemeVariant::DARK,
      'green' => ThemeVariant::GREEN,
      'purple' => ThemeVariant::PURPLE,
      default => ThemeVariant::LIGHT,
  };

  // Set text colors based on theme
  $textColor = $theme === 'dark' ? TextColor::LIGHT : TextColor::DARK;
  $eyebrowColor = TextColor::GREEN_NEON;
  $subtitleColor = $theme === 'dark' ? TextColor::GRAY : TextColor::GRAY;

  // Grid classes based on card count
  $cardCount = count($pricing_cards);
  $gridClasses = match ($cardCount) {
      1 => 'grid-cols-1 max-w-md mx-auto',
      2 => 'grid-cols-1 md:grid-cols-2 max-w-4xl mx-auto',
      4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
      default => 'grid-cols-1 md:grid-cols-3',
  };
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  {{-- Title Section --}}
  <div class="py-16 px-4 lg:px-16 text-center">
    <div class="max-w-[1100px] mx-auto">
      {{-- Eyebrow --}}
      @if($eyebrow)
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::SMALL"
          :color="$eyebrowColor"
          class="block mb-6 uppercase tracking-wider font-medium"
        >
          {{ $eyebrow }}
        </x-text>
      @endif

      {{-- Main Heading --}}
      @if($heading)
        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H2"
          :color="$textColor"
          class="mb-6"
        >
          {{ $heading }}
        </x-heading>
      @endif

      {{-- Subtitle --}}
      @if($subtitle)
        <x-text
          :as="TextTag::P"
          :size="TextSize::BASE"
          class="{{ $subtitleColor }} max-w-4xl mx-auto"
        >
          {{ $subtitle }}
        </x-text>
      @endif
    </div>
  </div>

  {{-- Cards Section --}}
  @if(!empty($pricing_cards))
    <div class="px-4 lg:px-16 pb-16 bg-primary-dark">
      <div class="grid {{ $gridClasses }} gap-6 max-w-7xl mx-auto items-start">
        @foreach($pricing_cards as $card)
          <x-pricing-card
            :icon="$card['icon'] ?? null"
            :title="$card['title'] ?? ''"
            :description="$card['description'] ?? ''"
            :pricing_items="$card['pricing_items'] ?? []"
            :cta="$card['cta'] ?? null"
            :features_title="$card['features_title'] ?? ''"
            :features="$card['features'] ?? []"
            :asterisk_note="$card['asterisk_note'] ?? ''"
            :is_popular="$card['is_popular'] ?? false"
          />
        @endforeach
      </div>
    </div>
  @endif
</x-section>