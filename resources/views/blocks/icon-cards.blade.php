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
      'green' => 'content-media-gradient',
      default => 'bg-white',
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

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }} overflow-visible">

  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$themeVariant"
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
                            class="text-primary-dark text-left font-extrabold 
                            relative after:content-[''] 
                            after:absolute after:bottom-[-12px] after:left-0 after:w-full after:h-[1px] 
                            after:bg-[linear-gradient(180deg,rgba(0,0,0,0.04)_0%,rgba(0,0,0,0.10)_100%)]"
                        >
                            {{ $card['title'] }}
                        </x-heading>
                        <x-text
                            :as="TextTag::P"
                            :size="TextSize::SMALL"
                            class="text-primary-dark text-left font-normal text-default"
                        >
                            {{ $card['text'] }}
                        </x-text>
                    </div>
                </div>
            @endforeach
        </x-grid>
    </x-container>
  
</x-section>
