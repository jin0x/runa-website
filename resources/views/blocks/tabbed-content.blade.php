@php
  /**
   * Tabbed Content Block
   */
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Enums\ContainerSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\ButtonVariant;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Format tabs for the tabs component
  $formattedTabs = [];
  foreach ($tabs as $tab) {
      ob_start();
@endphp
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center justify-self-center max-w-5xl">
        <div class="order-2 lg:order-1">
          @if(!empty($tab['content_data']['heading']))
            <x-heading
              :as="HeadingTag::H2"
              :size="HeadingSize::H2"
              class="mb-4"
            >
              {{ $tab['content_data']['heading'] }}
            </x-heading>
          @endif

          @if(!empty($tab['content_data']['subtitle']))
            <x-text
              :as="TextTag::P"
              :size="TextSize::MEDIUM"
              class="mb-6 opacity-90"
            >
              {{ $tab['content_data']['subtitle'] }}
            </x-text>
          @endif

          @if(!empty($tab['content_data']['text']))
            <x-text
              :as="TextTag::DIV"
              :size="TextSize::LARGE"
              class="prose prose-lg max-w-none mb-6"
            >
              {!! $tab['content_data']['text'] !!}
            </x-text>
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

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  <x-container :size="ContainerSize::XLARGE">

    @if(!empty($section_heading['eyebrow']) || !empty($section_heading['heading']) || !empty($section_heading['subtitle']))
      <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_title"
        :subtitle="$section_description"
        :eyebrow="$section_heading['eyebrow']"
        :heading="$section_heading['heading']"
        :subtitle="$section_heading['subtitle']"
        :variant="$sectionHeadingVariant"
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
