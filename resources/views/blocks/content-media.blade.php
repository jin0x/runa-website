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

  // Media classes - different for videos vs images
  $mediaClasses = $media_type === 'video' ? 'w-[1000px] aspect-video' : 'object-cover h-[294px] xl:h-[650px] w-full';

  $media_url = '';
  if ($media_type === 'video') {
          // Use uploaded file
          $video = get_field('right_media_video');
          $media_url = (!empty($video) && is_array($video)) ? ($video['url'] ?? '') : '';
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
    <div class="max-w-7xl {{ $media_type === 'video' ?  "flex flex-col" : "grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1fr_1fr] gap-12 lg:gap-18" }} items-center justify-center mx-auto">
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
          <x-text
            :as="TextTag::DIV"
            :size="TextSize::MEDIUM"
            class="mb-8 prose max-w-none"
          >
            {!! $content_text !!}
          </x-text>
        @endif

        @if(!empty($list_items))
          <ul class="mb-8 space-y-4">
            @foreach($list_items as $item)
              <li class="flex gap-3 items-center">
                {{-- SVG Icon Placeholder - Replace with your actual SVG --}}
                <span class="flex-shrink-0">
                  <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="16" fill="url(#paint0_linear_1564_27418)"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.8937 19.9874L22.3049 10.5762L23.3655 11.6368L13.424 21.5784L12.8937 22.1087L12.3633 21.5784L7.39258 16.6076L8.45324 15.5469L12.8937 19.9874Z" fill="black"/>
                    <defs>
                      <linearGradient id="paint0_linear_1564_27418" x1="0" y1="32" x2="32" y2="0" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#00FFA3"/>
                        <stop offset="0.48313" stop-color="#93FF82"/>
                        <stop offset="0.943979" stop-color="#EEFC51"/>
                      </linearGradient>
                    </defs>
                  </svg>
                </span>
                <x-text
                  :as="TextTag::SPAN"
                  :size="TextSize::MEDIUM"
                  class="flex-1"
                >
                  {{ $item['list_item_text'] }}
                </x-text>
              </li>
            @endforeach
          </ul>
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

      {{-- Right Side - Media with media anchor --}}
      <div id="media" class="order-2">
        <x-media
          :mediaType="$media_type"
          :mediaUrl="$media_url"
          :classes="$mediaClasses"
          containerClasses="overflow-hidden rounded-lg"
        />
      </div>
    </div>
  </x-container>
</x-section>

{{-- Smooth Scroll JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle smooth scroll for hash links
    function smoothScrollToAnchor(hash) {
        const target = document.querySelector(hash);
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
                inline: 'nearest'
            });
        }
    }

    // Handle clicks on anchor links
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href*="#media"]');
        if (link) {
            e.preventDefault();
            smoothScrollToAnchor('#media');
            // Update URL without triggering page reload
            history.pushState(null, null, link.href);
        }
    });

    // Handle direct page loads with hash
    if (window.location.hash === '#media') {
        setTimeout(() => {
            smoothScrollToAnchor('#media');
        }, 500); // Small delay to ensure page and videos are fully loaded
    }
});
</script>