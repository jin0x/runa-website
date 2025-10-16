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
  use App\Enums\SectionSize;
  use App\Enums\TextColor;

  // Handle background image URL
  $bg_image_url = '';
  if (!empty($background_image) && is_array($background_image)) {
      $bg_image_url = $background_image['url'] ?? '';
  } elseif (!empty($background_image)) {
      $bg_image_url = $background_image; // Direct URL string
  }

  // Set height based on compact option
  $heightClass = $compact ? 'h-[600px]' : 'h-screen';

  // Define button variants
  $primaryButtonVariant = ButtonVariant::PRIMARY;
  $secondaryButtonVariant = ButtonVariant::SECONDARY;

  // Define accent color for highlighted text in title
  $accentColor = 'text-gradient-primary';

  // Determine if content should be full-width or contained
  $isFullWidth = $content_width === 'full-width';

  // background position (focus area of the image)
  $positionClass = match($background_position) {
    'top left' => 'object-left-top',
    'top center' => 'object-top',
    'top right' => 'object-right-top',
    'center left' => 'object-left',
    'center center' => 'object-center',
    'center right' => 'object-right',
    'bottom left' => 'object-left-bottom',
    'bottom center' => 'object-bottom',
    'bottom right' => 'object-right-bottom',
    default => 'object-center',
  };
@endphp

<x-section :size="SectionSize::NONE" classes="relative w-full {{ $heightClass }} overflow-hidden {{ $block->classes ?? '' }}">
  {{-- Background Image --}}
  <div class="absolute inset-0 w-full h-full z-0">
    @if (!empty($bg_image_url))
      <img
        src="{{ $bg_image_url }}"
        alt="{{ $title ? strip_tags($title) : get_bloginfo('name') }}"
        class="absolute inset-0 object-cover w-full h-full {{ $positionClass }}"
      >
      @if (!empty($overlay_color))
        <div
          class="absolute inset-0 pointer-events-none"
          style="
            background: linear-gradient(
              to right,
              {{ $overlay_color }} 0%,
              rgba(0, 0, 0, 0) {{ ($overlay_opacity ?? 50) }}%
            );
          ">
        </div>
      @endif
    @else
      {{-- Fallback background if no image is provided --}}
      <div class="absolute inset-0 bg-primary-dark"></div>
    @endif
  </div>

  {{-- Content Container --}}
  <div class="absolute bottom-0 left-0 right-0 z-20 pb-16 px-4 lg:px-8">
    @if($isFullWidth)
      {{-- Full Width: No container, just padding --}}
      <x-flex direction="col">
        @if ($eyebrow)
        <div>
          <x-text
            :as="TextTag::SPAN"
            :size="TextSize::EYEBROW"
            :color="TextColor::GRADIENT"
            class="inline-block uppercase mb-3"
            >
            {{ $eyebrow }}
          </x-text>
        </div>
        @endif

        @if ($title)
          <x-heading
            :as="HeadingTag::H1"
            :size="HeadingSize::SUPER_DUPER"
            :color="TextColor::DARK"
            class="mb-3"
          >
            {!! preg_replace('/<span>(.*?)<\/span>/', '<span class="' . $accentColor . '">$1</span>', $title) !!}
          </x-heading>
        @endif

        @if ($content)
          <x-text
            :as="TextTag::SPAN"
            :size="TextSize::XLARGE"
            :color="TextColor::DARK"
            class="mb-8"
          >
            {!! $content !!}
          </x-text>
        @endif

        @if (!empty($ctas))
          <div class="flex flex-wrap gap-3">
            @foreach ($ctas as $index => $button)
              @php
                $button_label = $button['cta']['title'] ?? null;
                $button_link = $button['cta']['url'] ?? null;
                $button_target = $button['cta']['target'] ?? '_self';
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
      </x-flex>
    @else
      {{-- Contained: Within container --}}
      <x-container>
        <x-flex direction="col">
          @if ($eyebrow)
          <div>
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::EYEBROW"
              :color="TextColor::GRADIENT"
              class="inline-block uppercase mb-3"
              >
              {{ $eyebrow }}
            </x-text>
          </div>
          @endif

          @if ($title)
            <x-heading
              :as="HeadingTag::H1"
              :size="HeadingSize::SUPER_DUPER"
              :color="TextColor::DARK"
              class="mb-3"
            >
              {!! preg_replace('/<span>(.*?)<\/span>/', '<span class="' . $accentColor . '">$1</span>', $title) !!}
            </x-heading>
          @endif

          @if ($content)
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::XLARGE"
              :color="TextColor::DARK"
              class="mb-8"
            >
              {!! $content !!}
            </x-text>
          @endif

          @if (!empty($ctas))
            <div class="flex flex-wrap gap-3">
              @foreach ($ctas as $index => $button)
                @php
                  $button_label = $button['cta']['title'] ?? null;
                  $button_link = $button['cta']['url'] ?? null;
                  $button_target = $button['cta']['target'] ?? '_self';
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
        </x-flex>
      </x-container>
    @endif
  </div>
</x-section>
