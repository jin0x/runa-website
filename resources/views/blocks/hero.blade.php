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

  // Handle background image URL
  $bg_image_url = '';
  if (!empty($background_image) && is_array($background_image)) {
      $bg_image_url = $background_image['url'] ?? '';
  } elseif (!empty($background_image)) {
      $bg_image_url = $background_image; // Direct URL string
  }

  // Set height based on compact option
  $heightClass = $compact ? 'min-h-[600px]' : 'min-h-screen';

  // Define button variants
  $primaryButtonVariant = ButtonVariant::GREEN;
  $secondaryButtonVariant = ButtonVariant::TRANSPARENT;

  // Define accent color for highlighted text in title
  $accentColor = 'text-primary-lime';
@endphp

<section class="relative w-full {{ $heightClass }} overflow-hidden">
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


  </div>

  {{-- Content Container --}}
  <x-container :size="ContainerSize::XLARGE">
    <div class="flex flex-col p-8 md:p-16">
      @if ($eyebrow)
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::SMALL"
          class="inline-block px-4 py-2 rounded-full border border-primary-green-neontext-white mb-3"
        >
          {{ $eyebrow }}
        </x-text>
      @endif
      @if ($title)
        <x-heading
          :as="HeadingTag::H1"
          :size="HeadingSize::H1"
          class="text-white mb-3"
        >
          {!! preg_replace('/<span>(.*?)<\/span>/', '<span class="' . $accentColor . '">$1<span>', $title) !!}
        </x-heading>
      @endif
      @if ($content)
        <x-text
          :as="TextTag::P"
          :size="TextSize::XLARGE"
          class="text-white mb-8 max-w-2xl"
        >
          {!! $content !!}
        </x-text>
      @endif
      @if (!empty($ctas))
        <div class="flex flex-wrap gap-2">
          @foreach ($ctas as $index => $button)
            @php
              $button_label = $button['cta']['title'] ?? null;
              $button_link = $button['cta']['url'] ?? null;
              $button_target = $button['cta']['target'] ?? '_self';
              // First button is primary, second is secondary
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
  </x-container>
</section>