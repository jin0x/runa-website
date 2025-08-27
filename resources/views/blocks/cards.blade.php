@php
  /**
   * Cards Block
   */
  use App\Enums\SectionSize;
  use App\Enums\ThemeVariant;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\FontType;

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
  $themeVariant = $theme === 'dark' ? ThemeVariant::DARK : ThemeVariant::LIGHT;

  // Set background color based on theme
  $bgColor = match ($theme) {
      'dark' => 'bg-primary-dark',
      default => 'bg-white',
  };

  // Set grid column values
  $gridColumns = $columns === '2' ? '2' : '3';

  // Set gap sizes based on columns
  $gapSize = 'lg';
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">
  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$themeVariant"
      classes="mb-12"
    />
  @endif

  @if(!empty($cards))
    <x-grid
      :columns="$gridColumns"
      :gapsize="$gapSize"
    >
      @foreach($cards as $card)
        <x-card
          :title="$card['title']"
          :eyebrow="$card['eyebrow'] ?? ''"
          :excerpt="$card['excerpt'] ?? ''"
          :image="$card['image'] ?? ''"
          :cta="[
            'label' => $card['cta']['title'] ?? 'Read more',
            'url' => $card['cta']['url'] ?? '#',
            'target' => $card['cta']['target'] ?? '_self'
          ]"
          :headingTag="HeadingTag::H3"
          :headingSize="HeadingSize::H4"
          :headingFont="FontType::SANS"
        />
      @endforeach
    </x-grid>
  @endif
</x-section>
