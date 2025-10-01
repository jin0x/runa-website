@php
  /**
   * Callout Simple
   */
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\ButtonVariant;
  use App\Helpers\EnumHelper;


  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Set text color enums based on theme
  $headingColor = $themeVariant === ThemeVariant::DARK ? TextColor::LIGHT : TextColor::DARK;
  $contentColor = $themeVariant === ThemeVariant::DARK ? TextColor::LIGHT : TextColor::DARK;
  
  // Always use centered layout as per Figma design
  $contentClasses = 'flex flex-col gap-6 p-8 lg:p-16 content-end';
  $textContainerClasses = 'lg:max-w-[469px]';
  $buttonsContainerClasses = 'flex flex-wrap gap-4';

  // Background image styles
  $hasBackgroundImage = !empty($background_image);
  $sectionClasses = $hasBackgroundImage ? 'relative overflow-hidden' : '';
  $backgroundImageUrl = $hasBackgroundImage ? $background_image['url'] : null;

@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }} overflow-visible">
  <x-container classes="relative z-10 !px-0 min-h-[380px] flex items-end rounded-3xl overflow-hidden">
    @if($hasBackgroundImage)
      <div
        class="absolute inset-x-0 bottom-0 h-full bg-cover bg-center bg-no-repeat pointer-events-none"
        style="background-image: url('{{ $backgroundImageUrl }}'); background-position: center bottom; z-index:-1;"
      ></div>
    @else
      <div class="absolute inset-x-0 bottom-0 h-full pointer-events-none" style="z-index:-1;"></div>
    @endif
    <div class="{{ $contentClasses }}">
      <div class="{{ $textContainerClasses }}">

        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::MEDIUM"
          class="inline-block mb-3 font-semibold text-gradient-primary uppercase"
        >
          {{ $eyebrow }}
        </x-text>

        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H4"
          :color="$headingColor"
          class="mb-4"
        >
          {{ $title }}
        </x-heading>

        @if($content)
          <x-text
            :as="TextTag::DIV"
            :size="TextSize::LARGE"
            :color="$contentColor"
            class="prose prose-lg max-w-none"
          >
            {!! $content !!}
          </x-text>
        @endif
      </div>

      @if(!empty($ctas))
        <div class="{{ $buttonsContainerClasses }}">
          @foreach($ctas as $index => $button)
            @php
              $button_label = $button['cta']['title'] ?? null;
              $button_link = $button['cta']['url'] ?? null;
              $button_target = $button['cta']['target'] ?? '_self';

              // First button is primary, others are secondary
              $currentButtonVariant = $index === 0 ? ButtonVariant::PRIMARY : ButtonVariant::SECONDARY;
            @endphp

            @if(!empty($button_label) && !empty($button_link))
              <x-button
                :variant="$currentButtonVariant"
                :href="$button_link"
                target="{{ $button_target }}"
              >
                {{ $button_label }}
              </x-button>
            @endif
          @endforeach
        </div>
      @endif
    </div>
  </x-container>
</x-section>
