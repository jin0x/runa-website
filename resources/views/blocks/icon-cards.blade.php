@php
  /**
   * Icon Cards
   */
  use App\Enums\ThemeVariant;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;
  use App\Enums\ContainerSize;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Set heading color based on theme
  $headingColor = match ($themeVariant) {
      ThemeVariant::DARK => TextColor::GRADIENT,
      default => TextColor::DARK,
  };

  $cardTextColor = match ($themeVariant) {
      ThemeVariant::DARK => TextColor::LIGHT,
      default => TextColor::LIGHT,
  };

  // Set grid column values
  $gridColumns = match($columns) {
    '2' => '2',
    '3' => '3',
    '4' => '4',
    default => '3', // Default to 3 columns if invalid value
};

  // Set gap sizes based on columns
$gapSize = 'lg';

  // Cards Background using standardized helper with ThemeVariant
  // (Note: background is now handled by icon-card component)

@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }} overflow-visible">
  <x-container>
    @if($section_eyebrow || $section_title || $section_description)
      <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_title"
        :subtitle="$section_description"
        :variant="$sectionHeadingVariant"
        classes="mb-12"
      />
    @endif

    <x-grid :columns="$gridColumns" :rowgapsize="$gapSize" colgapsize="lg" classes="mt-12">
        @foreach($cards as $card)
            <x-icon-card
                :icon="$card['icon']"
                :title="$card['title']"
                :text="$card['text']"
                :cardColor="$card_color"
                :textColor="$cardTextColor"
                :cta="$card['cta'] ?? null"
            />
        @endforeach
    </x-grid>
  </x-container>
</x-section>
