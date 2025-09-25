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

   // Set text color based on theme
  $textColor = match ($theme) {
      'dark' => 'text-white',
      default => 'text-primary-navy',
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
        <x-grid columns="3" classes="mt-12">
            @foreach($steps as $step)
                <div class="flex flex-col overflow-hidden gap-y-6">
                    @if ( $step['image'] )
                        <div class="w-full overflow-hidden rounded-3xl font-">
                            @php
                                $step_image_alt= !empty($step['image']['alt']) ? $step['image']['alt'] : $step['title'];
                            @endphp
                            <img
                                src="{{ $step['image']['url'] }}"
                                alt="{{ $step_image_alt }}"
                                class="object-cover h-full lg:h-[210px] xl:h-[300px] 2xl:h-full w-full"
                            >
                        </div>
                    @endif
                    <div class="flex flex-col flex-1 gap-y-3">
                      @php
                        $headingColors = [
                          'text-secondary-pink',
                          'text-secondary-cyan',
                          'text-primary-green-neon',
                        ];

                        $colorClass = $headingColors[($loop->iteration - 1) % count($headingColors)];
                      @endphp
                        <x-heading
                            id="main-title"
                            :as="HeadingTag::H3"
                            :size="HeadingSize::H3"
                            class="{{ $colorClass }} text-center font-bold"
                        >
                            {{ $step['title'] }}
                        </x-heading>
                        <x-text
                            :as="TextTag::P"
                            :size="TextSize::SMALL"
                            class="{{ $textColor }} text-center font-normal text-default"
                        >
                            {{ $step['text'] }}
                        </x-text>
                    </div>
                </div>
            @endforeach
        </x-grid>
    </x-container>

</x-section>
