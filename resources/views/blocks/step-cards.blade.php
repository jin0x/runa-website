@php
  /**
   * Step Cards
   */
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\TextColor;

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
      'dark' => ThemeVariant::DARK,
      'green' => ThemeVariant::GREEN_GRADIENT,
      default => ThemeVariant::LIGHT,
  };

  // Section heading text color based on theme
  $sectionHeadingColor = match ($theme) {
      'dark' => TextColor::GRADIENT,
      default => TextColor::DARK,
  };

   // Set text color based on theme
  $textColor = match ($theme) {
      'dark' => TextColor::LIGHT,
      default => TextColor::DARK,
  };

  $cardColors = [
      'bg-primary-green-soft',
      'bg-secondary-pink',
      'bg-secondary-cyan',
      'bg-primary-yellow',
  ];

  $headingColors = [
      TextColor::GREEN_SOFT,
      TextColor::GREEN_NEON, // Using available color instead of pink
      TextColor::GREEN_NEON, // Using available color instead of cyan
      TextColor::GREEN_SOFT, // Using available color instead of yellow
  ];

@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }} overflow-visible">

  @if($section_eyebrow || $section_title || $section_description)
    <div class="max-w-4xl justify-self-center mb-12 text-center">
        <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_title"
        :subtitle="$section_description"
        :variant="$themeVariant"
        :color="$sectionHeadingColor"
        classes="mb-12"
        />
    </div>
  @endif

    <x-container classes="!px-0">
        <x-grid columns="3" classes="mt-12">
            @foreach($steps as $step)
                @php
                    // Get color for this card based on index
                    $cardBgColor = $cardColors[$loop->index % 4];
                @endphp

                <div class="flex flex-col overflow-hidden gap-y-6 p-6">
                    @if ( $step['image'] )
                        <div class="w-full overflow-hidden rounded-3xl {{ $cardBgColor }}">
                            @php
                                $step_image_alt= !empty($step['image']['alt']) ? $step['image']['alt'] : $step['title'];
                            @endphp
                            <img
                                src="{{ $step['image']['url'] }}"
                                alt="{{ $step_image_alt }}"
                                class="object-cover h-full w-full aspect-[1/1]"
                            >
                        </div>
                    @endif
                    <div class="flex flex-col flex-1 gap-y-3">
                      @php
                        $headingColor = $headingColors[($loop->iteration - 1) % count($headingColors)];
                      @endphp
                        <x-heading
                            id="main-title"
                            :as="HeadingTag::H4"
                            :size="HeadingSize::H4"
                            :color="$headingColor"
                            class="text-left font-bold"
                        >
                            {{ $step['title'] }}
                        </x-heading>
                        <x-text
                            :as="TextTag::P"
                            :size="TextSize::SMALL"
                            :color="$textColor"
                            class="text-left font-normal text-default"
                        >
                            {{ $step['text'] }}
                        </x-text>
                    </div>
                </div>
            @endforeach
        </x-grid>
    </x-container>

</x-section>
