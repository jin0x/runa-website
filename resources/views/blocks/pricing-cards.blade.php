@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionHeadingVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Pricing block special case: Use MIXED_GREEN_LIGHT for dark theme
  $sectionHeadingVariant = ($themeVariant === ThemeVariant::DARK)
      ? SectionHeadingVariant::MIXED_GREEN_LIGHT
      : EnumHelper::getSectionHeadingVariant($themeVariant);

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
  {{-- Section Heading --}}
  @if($eyebrow || $heading || $subtitle)
    <x-section-heading
      :eyebrow="$eyebrow"
      :heading="$heading"
      :subtitle="$subtitle"
      :variant="$sectionHeadingVariant"
      classes="py-16 px-4 lg:px-16"
      isShowcase="true"
    />
  @endif

  {{-- Cards Section --}}
  @if(!empty($pricing_cards))
    <div class="pb-16">
      <div class="grid {{ $gridClasses }} gap-6 items-start">
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
