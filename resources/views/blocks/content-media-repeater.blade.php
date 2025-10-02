@php
  /**
   * Content Media Repeater
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\SectionHeadingVariant;
  use App\Helpers\EnumHelper;
  use function App\Helpers\apply_tailwind_classes_to_content;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // Theme-based color enums
  $buttonVariant = ButtonVariant::PRIMARY;
  $secondaryButtonVariant = ButtonVariant::SECONDARY;

  // Media classes
  $mediaClasses = 'w-full object-cover h-full min-h-[294px] xl:min-h-[640px]';

@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }} overflow-visible">

  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$sectionHeadingVariant"
      classes="mb-12"
    />
  @endif

    @foreach($items as $item)
      @php
        // Media Validations
        $media_url = '';
        $media_type = $item['media_type'] ?? null;
        $image = $item['image'] ?? null;
        $video = $item['video'] ?? null;
        $lottie = $item['lottie'] ?? null;

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

        // Order Validations
        $reverse_layout = !empty($item['reverse_layout']);
        if ($reverse_layout) {
            $content_order = 'order-1 md:order-2';
            $media_order   = 'order-2 md:order-1';

            $grid_layout = 'md:grid-cols-[6.7fr_3.3fr]';
        } else {
            $is_even = $loop->iteration % 2 == 0;

            $content_order = 'order-1 ' . ($is_even ? 'md:order-2' : 'md:order-1');
            $media_order   = 'order-2 ' . ($is_even ? 'md:order-1' : 'md:order-2');

            // si es par a la derecha
            $grid_layout = $is_even ? 'xl:grid-cols-[6.7fr_3.3fr]' : 'xl:grid-cols-[3.3fr_6.7fr]';
        }
      @endphp
      <div class="grid grid-cols-1 md:grid-cols-2 {{ $grid_layout }} gap-12 lg:gap-18 items-center {{ !$loop->last ? 'mb-12' : '' }}">
        {{-- Content Section --}}
        <div class="{{ $content_order }} flex flex-col">
          @if(!empty($item['content_eyebrow']))
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::SMALL"
              class="inline-block mb-4"
            >
              {{ $item['content_eyebrow'] }}
            </x-text>
          @endif

          @if(!empty($item['content_text']))
            <x-text
              :as="TextTag::DIV"
              :size="TextSize::BASE"
              class="mb-8"
            >
              {!! apply_tailwind_classes_to_content($item['content_text'], [
                  'heading' => '!font-normal',
                  'strong'  => '!font-extrabold',
              ]) !!}
            </x-text>
          @endif

          @if(!empty($item['ctas']))
            <div class="flex flex-wrap gap-4 mt-2">
              @foreach($item['ctas'] as $index => $button)
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

        {{-- Media Section --}}
        <x-media
          :mediaType="$item['media_type']"
          :mediaUrl="$media_url"
          :classes="$mediaClasses"
          :containerClasses="$media_order . ' overflow-hidden rounded-[48px]'"
        />
      </div>
    @endforeach

</x-section>
