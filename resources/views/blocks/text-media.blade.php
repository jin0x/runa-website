@php
  /**
   * Text / Media Block
   */
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
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
      'light' => ThemeVariant::LIGHT,
      'dark' => ThemeVariant::DARK,
      'green' => ThemeVariant::GREEN,
      'purple' => ThemeVariant::PURPLE,
      default => ThemeVariant::LIGHT,
  };

  // Media handling
  $media_url = '';
  if ($media_type === 'video' && !empty($video) && is_array($video)) {
      $media_url = $video['url'] ?? '';
  } elseif ($media_type === 'image' && !empty($image)) {
      if (is_array($image)) {
          $media_url = $image['url'] ?? '';
      } else {
          $media_url = $image; // Direct URL string
      }
  } elseif ($media_type === 'lottie' && !empty($lottie) && is_array($lottie)) {
      $media_url = $lottie['url'] ?? '';
  }

  // Theme-based text colors
  $eyebrowColor = $theme === 'dark' ? TextColor::GREEN_NEON : TextColor::GREEN_SOFT;
  $headingColor = $theme === 'dark' ? TextColor::LIGHT : TextColor::DARK;
  $textColor = $theme === 'dark' ? TextColor::LIGHT : TextColor::GRAY;
  $buttonVariant = ButtonVariant::PRIMARY;
  $secondaryButtonVariant = ButtonVariant::SECONDARY;

  // Media classes
  $mediaClasses = 'w-full object-cover h-full min-h-[425px] xl:min-h-[425px] xl:max-h-[712px]';
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$themeVariant"
      classes="mb-12"
    />
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
    @php
      // Always order-1 for mobile, but on desktop it depends on reverse_layout
      $media_order = 'order-1 ' . ($reverse_layout ? 'md:order-2' : 'md:order-1');
      $content_order = 'order-2 ' . ($reverse_layout ? 'md:order-1' : 'md:order-2');
    @endphp

    {{-- Media Section --}}
    <x-media
      :mediaType="$media_type"
      :mediaUrl="$media_url"
      :altText="$content_heading"
      :classes="$mediaClasses"
      :containerClasses="$media_order . ' overflow-hidden'"
    />

    {{-- Content Section --}}
    <div class="{{ $content_order }} flex flex-col">
      @if($content_eyebrow)
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::SMALL"
          :color="$eyebrowColor"
          class="inline-block mb-4"
        >
          {{ $content_eyebrow }}
        </x-text>
      @endif

      @if($content_heading)
        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H2"
          :color="$headingColor"
          class="mb-6"
        >
          {!! $content_heading !!}
        </x-heading>
      @endif

      @if($content_text)
        <x-text
          :as="TextTag::DIV"
          :size="TextSize::BASE"
          :color="$textColor"
          class="mb-8"
        >
          {!! $content_text !!}
        </x-text>
      @endif

      @if(!empty($ctas))
        <div class="flex flex-wrap gap-4 mt-2">
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
  </div>
</x-section>
