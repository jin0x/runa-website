@php
  /**
   * Split Hero
   * 50/50 split layout with content and media side by side
   */
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
  use App\Enums\SectionSize;
  use App\Enums\TextColor;

  // Set height based on compact option
  $heightClass = $compact ? 'h-[600px]' : 'h-screen';

  // Define button variants
  $primaryButtonVariant = ButtonVariant::PRIMARY;
  $secondaryButtonVariant = ButtonVariant::SECONDARY;

  // Define accent color for highlighted text in title
  $accentColor = 'text-gradient-primary';

  // Media URL processing
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

  // Media classes
  $mediaClasses = 'w-full h-full object-cover';
@endphp

<x-section :size="SectionSize::NONE" classes="bg-primary-dark relative w-full {{ $heightClass }} overflow-hidden {{ $block->classes ?? '' }}">
  <div class="grid grid-cols-1 lg:grid-cols-2 h-full">
    
    {{-- Content Section (Left) --}}
    <div class="flex items-center justify-center py-16 lg:py-24 z-20">
      <x-container>
        <x-flex direction="col">
          @if ($eyebrow)
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::SMALL"
              class="inline-block text-gradient-primary uppercase mb-3"
            >
              {{ $eyebrow }}
            </x-text>
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
    </div>

    {{-- Media Section (Right) --}}
    <div class="relative overflow-hidden">
      @if(!empty($media_url))
        <x-media
          :mediaType="$media_type"
          :mediaUrl="$media_url"
          :altText="$title ?? 'Split Hero media'"
          :classes="$mediaClasses"
          containerClasses="w-full h-full"
        />
      @else
        {{-- Fallback if no media provided --}}
        <div class="w-full h-full bg-gradient-to-br from-neutral-800 to-neutral-900"></div>
      @endif
    </div>

  </div>
</x-section>