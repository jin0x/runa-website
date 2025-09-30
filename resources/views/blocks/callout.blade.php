@php
  /**
   * Callout Block
   */
  use App\Enums\SectionSize;
  use App\Enums\ContainerSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
  use App\Enums\ThemeVariant;

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
      default => 'bg-primary-light',
  };

  // Set text color based on theme
  $textColor = match ($theme) {
      'dark' => 'text-white',
      default => 'text-primary-navy',
  };

  // Always use centered layout as per Figma design
  $contentClasses = 'flex flex-col text-center items-center gap-6';
  $textContainerClasses = 'max-w-3xl';
  $buttonsContainerClasses = 'flex flex-wrap justify-center gap-4';

  // Background image styles
  $hasBackgroundImage = !empty($background_image);
  $sectionClasses = $hasBackgroundImage ? 'relative overflow-hidden min-h-[600px]' : '';
  $backgroundImageUrl = $hasBackgroundImage ? $background_image['url'] : null;
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $sectionClasses }} {{ $block->classes }}">
  @if($hasBackgroundImage)
    <div
      class="absolute inset-x-0 bottom-0 h-full bg-cover bg-center bg-no-repeat pointer-events-none"
      style="background-image: url('{{ $backgroundImageUrl }}'); background-position: center bottom;"
    ></div>
  @endif

  <x-container :size="ContainerSize::XLARGE" classes="relative z-10">
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
          :size="HeadingSize::H3"
          class="mb-4 {{ $textColor }}"
        >
          {{ $title }}
        </x-heading>

        @if($content)
          <div class="prose prose-lg {{ $textColor }} max-w-none">
            {!! $content !!}
          </div>
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
