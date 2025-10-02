@php
  /**
   * Icon Cards
   */
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;

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
  $gridColumns = $columns === '2' ? '2' : '3';

  // Set gap sizes based on columns
  $gapSize = 'lg';

  // Cards Background
  $bgCards = match ($cards_background) {
    'cyan' => 'bg-secondary-cyan',
    'green' => 'bg-primary-green-soft',
    default => 'bg-secondary-cyan',
   };

@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }} overflow-visible">

  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$sectionHeadingVariant"
      classes="mb-12"
    />
  @endif

    <x-container classes="!px-0">
        <x-grid :columns="$gridColumns" :rowgapsize="$gapSize" colgapsize="lg" classes="mt-12">
            @foreach($cards as $card)
                <div class="flex flex-col overflow-hidden gap-y-10 p-6 rounded-lg {{ $bgCards }}">
                    @if ( $card['icon'] )
                        <div class="w-full overflow-hidden">
                            @php
                                $card_image_alt= !empty($card['icon']['alt']) ? $card['icon']['alt'] : $card['title'];
                            @endphp
                            <img
                                src="{{ $card['icon']['url'] }}"
                                alt="{{ $card_image_alt }}"
                                class="object-contain h-12 w-12"
                            >
                        </div>
                    @endif
                    <div class="flex flex-col flex-1 gap-y-6 min-h-[153px]">
                        <x-heading
                            id="main-title"
                            :as="HeadingTag::H4"
                            :size="HeadingSize::H4"
                            :color="$cardTextColor"
                            class="text-left font-extrabold
                            relative after:content-['']
                            after:absolute after:bottom-[-12px] after:left-0 after:w-full after:h-[1px]
                            after:bg-[linear-gradient(180deg,rgba(0,0,0,0.04)_0%,rgba(0,0,0,0.10)_100%)]"
                        >
                            {{ $card['title'] }}
                        </x-heading>
                        <x-text
                            :as="TextTag::P"
                            :size="TextSize::SMALL"
                            :color="$cardTextColor"
                            class="text-left font-normal text-default"
                        >
                            {{ $card['text'] }}
                        </x-text>
                    </div>
                </div>
            @endforeach
        </x-grid>
    </x-container>

</x-section>
