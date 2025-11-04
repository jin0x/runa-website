@php
  /**
   * Content Media Block
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ButtonVariant;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\ArchPosition;
  use App\Enums\SectionHeadingVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert arch position string to ArchPosition enum
  $archPositionValue = EnumHelper::getArchPosition($arch_position ?? 'none');

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Theme-based button variants
  $buttonVariant = ButtonVariant::PRIMARY;
  $secondaryButtonVariant = ButtonVariant::SECONDARY;

  // Media classes
  $mediaClasses = 'object-cover h-[294px] xl:h-[650px] w-full';

  // Media URL validation
  $media_url = '';
  if ($media_type === 'video' && !empty($video) && is_array($video)) {
      $media_url = $video['url'] ?? '';
  } elseif ($media_type === 'image' && !empty($image)) {
      if (is_array($image)) {
          $media_url = $image['url'] ?? '';
      } else {
          $media_url = $image;
      }
  } elseif ($media_type === 'lottie' && !empty($lottie) && is_array($lottie)) {
      $media_url = $lottie['url'] ?? '';
  }
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" :archPosition="$archPositionValue" classes="{{ $block->classes }} overflow-visible">
  <x-container>
    {{-- Section Heading at the top --}}
    @if($section_eyebrow || $section_heading || $section_subtitle)
      <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_heading"
        :subtitle="$section_subtitle"
        :variant="$sectionHeadingVariant"
        classes="mb-12"
      />
    @endif

    {{-- Split Content Layout --}}
    <div class="max-w-7xl grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1fr_1fr] gap-12 lg:gap-18 items-center justify-center mx-auto">
      {{-- Left Side - Content --}}
      <div class="flex flex-col order-1">
        @if(!empty($content_heading))
          <x-heading
            :as="HeadingTag::H3"
            :size="HeadingSize::H3"
            class="mb-6"
          >
            {{ $content_heading }}
          </x-heading>
        @endif

        @if(!empty($content_text))
          <div class="mb-8 prose max-w-none">
            {!! $content_text !!}
          </div>
        @endif

        @if(!empty($ctas))
          <div class="flex flex-wrap gap-4 mt-2">
            @foreach($ctas as $index => $button)
              @php
                $button_label = $button['cta']['title'] ?? null;
                $button_link = $button['cta']['url'] ?? null;
                $button_target = $button['cta']['target'] ?? '_self';
                $currentButtonVariant = $index === 0 ? $buttonVariant : $secondaryButtonVariant;
              @endphp

              @if($button_label && $button_link)
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

      {{-- Right Side - Media --}}
      <x-media
        :mediaType="$media_type"
        :mediaUrl="$media_url"
        :classes="$mediaClasses"
        containerClasses="order-2 overflow-hidden rounded-[48px]"
      />
    </div>
  </x-container>
</x-section>
