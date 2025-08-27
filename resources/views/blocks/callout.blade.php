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
      default => 'bg-white',
  };

  // Set text color based on theme
  $textColor = match ($theme) {
      'dark' => 'text-white',
      default => 'text-primary-navy',
  };

  // Set button variants based on theme
  $buttonVariant = $theme === 'dark' ? ButtonVariant::LIGHT : ButtonVariant::DARK;
  $secondaryButtonVariant = $theme === 'dark' ? ButtonVariant::TRANSPARENT : ButtonVariant::LIGHT;

  // Set the layout classes
  $contentClasses = $layout === 'side-by-side'
      ? 'flex flex-col md:flex-row items-center justify-between gap-8 md:gap-12'
      : 'flex flex-col text-center items-center gap-6';

  $textContainerClasses = $layout === 'side-by-side'
      ? 'flex-1 max-w-2xl'
      : 'max-w-3xl';

  $buttonsContainerClasses = $layout === 'side-by-side'
      ? 'flex flex-wrap gap-4 mt-6'
      : 'flex flex-wrap justify-center gap-4 mt-6';
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">
  <x-container :size="ContainerSize::XLARGE">
    <div class="{{ $contentClasses }}">
      <div class="{{ $textContainerClasses }}">
        @if($eyebrow)
          <x-text
            :as="TextTag::SPAN"
            :size="TextSize::SMALL"
            class="inline-block mb-3 font-semibold {{ $textColor }}"
          >
            {{ $eyebrow }}
          </x-text>
        @endif

        @if($title)
          <x-heading
            :as="HeadingTag::H2"
            :size="HeadingSize::H2"
            class="mb-4 {{ $textColor }}"
          >
            {{ $title }}
          </x-heading>
        @endif

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
              $currentButtonVariant = $index === 0 ? $buttonVariant : $secondaryButtonVariant;
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
