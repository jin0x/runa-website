@php
  /**
   * Tabbed Content Block
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

  // Format tabs for the tabs component
  $formattedTabs = [];
  foreach ($tabs as $tab) {
      ob_start();
@endphp
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
        <div class="order-2 lg:order-1">
          @if(!empty($tab['content_data']['heading']))
            <x-heading
              :as="HeadingTag::H3"
              :size="HeadingSize::H3"
              class="mb-4 {{ $textColor }}"
            >
              {{ $tab['content_data']['heading'] }}
            </x-heading>
          @endif

          @if(!empty($tab['content_data']['subtitle']))
            <x-text
              :as="TextTag::P"
              :size="TextSize::MEDIUM"
              class="mb-6 {{ $textColor }} opacity-90"
            >
              {{ $tab['content_data']['subtitle'] }}
            </x-text>
          @endif

          @if(!empty($tab['content_data']['text']))
            <div class="prose prose-lg {{ $textColor }} max-w-none mb-6">
              {!! $tab['content_data']['text'] !!}
            </div>
          @endif

          @if(!empty($tab['content_data']['ctas']))
            <div class="flex flex-wrap gap-4">
              @foreach($tab['content_data']['ctas'] as $index => $button)
                @php
                  $button_label = $button['cta']['title'] ?? null;
                  $button_link = $button['cta']['url'] ?? null;
                  $button_target = $button['cta']['target'] ?? '_self';

                  // First button is primary, others are secondary
                  $buttonVariant = $index === 0 ? ButtonVariant::PRIMARY : ButtonVariant::SECONDARY;
                @endphp

                @if(!empty($button_label) && !empty($button_link))
                  <x-button
                    :variant="$buttonVariant"
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

        <div class="order-1 lg:order-2">
          @if(!empty($tab['media']) && !empty($tab['media']['url']))
            <x-media
              :mediaType="$tab['media']['type']"
              :mediaUrl="$tab['media']['url']"
              :altText="$tab['media']['alt'] ?? ''"
              classes="w-full h-auto rounded-lg"
              containerClasses="overflow-hidden rounded-lg"
            />
          @endif
        </div>
      </div>
@php
      $tabContent = ob_get_clean();
      $formattedTabs[] = [
          'id' => $tab['id'],
          'label' => $tab['label'],
          'content' => $tabContent,
      ];
  }
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">
  <x-container :size="ContainerSize::XLARGE">

    @if(!empty($section_heading['eyebrow']) || !empty($section_heading['heading']) || !empty($section_heading['subtitle']))
      <x-section-heading
        :eyebrow="$section_heading['eyebrow']"
        :heading="$section_heading['heading']"
        :subtitle="$section_heading['subtitle']"
        :variant="$themeVariant"
        classes="mb-12"
      />
    @endif

    @if(!empty($formattedTabs))
      <x-tabs
        :tabs="$formattedTabs"
        variant="underline"
        class="w-full"
      />
    @endif

  </x-container>
</x-section>