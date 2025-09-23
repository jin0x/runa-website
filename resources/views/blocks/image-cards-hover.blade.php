@php
  /**
   * Image Cards Hover
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

@endphp

<x-section :size="$sectionSizeValue" classes="{{ $block->classes }} overflow-visible">

  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      classes="mb-12"
    />
  @endif

    <x-container classes="!px-0">
        <x-grid columns="2" classes="mt-12">
            @foreach($cards as $card)
                <div class="flex flex-col overflow-hidden rounded-3xl group relative">
                    @if ( $card['image'] )
                        <div class="w-full">
                            @php
                                $card_image_alt= !empty($card['image']['alt']) ? $card['image']['alt'] : $card['title'];
                            @endphp
                            <img
                                src="{{ $card['image']['url'] }}"
                                alt="{{ $card_image_alt }}"
                                class="object-cover h-[380px] md:h-[500px] xl:h-[700px] 2xl:h-full w-full"
                            >
                        </div>
                    @endif
                    <div class="flex flex-col flex-1 p-6 xl:p-12 bg-primary-green-neon xl:min-h-[188px]
                      relative
                      xl:absolute xl:left-0 xl:right-0 xl:w-full xl:bottom-0">
                        <x-heading
                            id="main-title"
                            :as="HeadingTag::H1"
                            :size="HeadingSize::H4"
                            class="text-left font-bold"
                        >
                            {!! $card['title'] !!}
                        </x-heading>
                        <x-text
                            :as="TextTag::P"
                            :size="TextSize::MEDIUM"
                            class="text-left font-normal text-default mt-3 xl:mt-0
                            overflow-hidden xl:max-h-0 xl:opacity-0 transition-all duration-300 ease-in-out group-hover:max-h-40 group-hover:opacity-100 group-hover:mt-3"
                        >
                            {{ $card['text'] }}
                        </x-text>
                    </div>
                </div>
            @endforeach
        </x-grid>
    </x-container>

</x-section>
