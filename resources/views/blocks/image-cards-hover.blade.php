@php
  /**
   * Image Cards Hover
   */
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

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

    <x-grid columns="2" classes="mt-12">
        @foreach($cards as $card)
            <x-image-hover-card
                :title="$card['title']"
                :text="$card['text']"
                :image="$card['image']"
                :cardColor="$card_color"
            />
        @endforeach
    </x-grid>
  </x-container>
</x-section>
