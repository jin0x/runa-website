@php
  /**
   * Hero Banner
   *
   */
  use App\Enums\ContainerSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;

  // Determine height based on compact option
  $heightClass = $compact ? 'h-[400px]' : 'h-screen max-h-[800px]';

  $contentClasses = 'max-w-[100ch]';

  // Handle media URL for right-side media component
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

  // Handle background image URL
  $bg_image_url = '';
  if (!empty($background_image) && is_array($background_image)) {
      $bg_image_url = $background_image['url'] ?? '';
  } elseif (!empty($background_image)) {
      $bg_image_url = $background_image; // Direct URL string
  }

  // Set text colors - for the hero banner, we'll use light text on dark background
  $textColor = 'text-white';
  $descriptionColor = 'text-primary-light';

  // Define button variants - primary and secondary
  $primaryButtonVariant = ButtonVariant::PRIMARY;
  $secondaryButtonVariant = ButtonVariant::SECONDARY;

  // Define accent color for highlighted text in title
  $accentColor = 'text-primary-lime';
@endphp

<div class="relative w-full {{ $heightClass }} overflow-hidden">
  {{-- Background Image --}}
  <div class="absolute inset-0 w-full h-full z-0">
    @if (!empty($bg_image_url))
      <img
        src="{{ $bg_image_url }}"
        alt="{{ $title ? strip_tags($title) : get_bloginfo('name') }}"
        class="absolute inset-0 object-cover w-full h-full"
      >
    @else
      {{-- Fallback background if no image is provided --}}
      <div class="absolute inset-0 bg-primary-dark"></div>
    @endif

    {{-- Gradient Overlay - black gradient from left to right --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent z-10"></div>
  </div>

  {{-- Content --}}
  <div class="relative z-20 h-full flex items-center">
    <x-container :size="ContainerSize::XLARGE">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="{{ $contentClasses }}">
          @if ($eyebrow)
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::SMALL"
              class="inline-block px-4 py-1 rounded-full border border-primary-lime {{ $textColor }} mb-6"
            >
              {{ $eyebrow }}
            </x-text>
          @endif

          @if ($title)
            <x-heading
              :as="HeadingTag::H1"
              :size="$compact ? HeadingSize::H1 : HeadingSize::DISPLAY_LARGE"
              class="{{ $textColor }}"
            >
              {!! preg_replace('/<span>(.*?)<\/span>/', '<span class="' . $accentColor . '">$1</span>', $title) !!}
            </x-heading>
          @endif

          @if ($content)
            <x-text
              :as="TextTag::P"
              :size="TextSize::LARGE"
              class="mt-6 {{ $descriptionColor }} max-w-2xl"
            >
              {!! $content !!}
            </x-text>
          @endif

          @if (!empty($ctas))
            <div class="mt-8 inline-flex flex-wrap gap-4">
              @foreach ($ctas as $index => $button)
                @php
                  $button_label = $button['cta']['title'] ?? null;
                  $button_link = $button['cta']['url'] ?? null;
                  $button_target = $button['cta']['target'] ?? '_self';

                  // Determine button variant based on position
                  $buttonVariant = $index === 0 ? $primaryButtonVariant : $secondaryButtonVariant;
                @endphp

                @if (!empty($button_label) && !empty($button_link))
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

        {{-- Right side media component --}}
        <div class="block">
          <x-media
            :mediaType="$media_type"
            :mediaUrl="$media_url"
            :altText="$title ? strip_tags($title) : 'Hero media'"
            classes="w-full object-cover h-full min-h-[425px] max-h-[600px]"
          />
        </div>
      </div>
    </x-container>
  </div>
</div>
