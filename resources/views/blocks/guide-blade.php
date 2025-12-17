@php
  /**
   * Guide Block - Comprehensive block with hero and guide sections
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ButtonVariant;
  use App\Enums\ContainerSize;
  use App\Enums\SectionSize;
  use App\Enums\TextColor;
@endphp

{{-- Hero Section --}}
@if($hero_title)
  @include('blocks.hero', [
    'eyebrow' => $hero_eyebrow,
    'title' => $hero_title,
    'content' => $hero_description,
    'ctas' => $hero_ctas,
    'media_type' => $media_type,
    'image' => $image,
    'video' => $video,
    'lottie' => $lottie,
    'background_image' => $background_image,
    'background_position' => $background_position,
    'overlay_color' => $overlay_color,
    'overlay_opacity' => $overlay_opacity,
    'content_width' => $content_width,
    'compact' => $compact,
  ])
@endif

{{-- Guide Content with TOC - Constrained Width --}}
@if(!empty($guide_sections) || !empty($toc_items))
  <div class="guide-content-wrapper max-w-[1600px] mx-auto px-16 py-16">
    <div class="flex justify-between">
      {{-- Table of Contents - Sticky Sidebar --}}
      <aside class="guide-toc w-86 flex-shrink-0 pr-12 sticky top-8 self-start">
        <div class="toc-container">
          <x-heading
            :as="HeadingTag::H3"
            :size="HeadingSize::H6_BOLD"
            class="uppercase mb-12 tracking-wider"
          >
            Table of Contents
          </x-heading>

          @if(!empty($toc_items))
            <nav class="toc-nav">
              <ul class="toc-list space-y-3">
                @foreach($toc_items as $item)
                  <li class="toc-item">
                    <a href="#{{ $item['anchor'] }}" class="toc-link flex items-center justify-between text-xs py-1 !no-underline">
                      <x-text :size="TextSize::MEDIUM">{{ $item['text'] }}</x-text>
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" class="min-w-[20px]">
                        <g clip-path="url(#clip0_4852_2584)">
                         <path d="M9.99992 3.33337L8.82492 4.50837L13.4749 9.16671H3.33325V10.8334H13.4749L8.82492 15.4917L9.99992 16.6667L16.6666 10L9.99992 3.33337Z" fill="black"/>
                        </g>
                        <defs>
                          <clipPath id="clip0_4852_2584">
                            <rect width="20" height="20" fill="white"/>
                          </clipPath>
                        </defs>
                      </svg>
                    </a>
                  </li>
                @endforeach
              </ul>
            </nav>
          @endif

          {{-- Download CTA --}}
          @if($download_link)
            <div class="toc-cta mt-12">
              <a href="{{ $download_link }}" target="_blank" class="inline-flex items-center gap-3 primary-button-gradient text-primary-dark px-6 py-3 rounded-full font-medium hover:bg-primary-green-neon/90 transition-colors !no-underline">
                <x-text :size="TextSize::SMALL">Download PDF</x-text>
              </a>
            </div>
          @endif
        </div>
      </aside>

      {{-- Main Content --}}
      <main class="guide-main-content flex-1 ml-6 max-w-5xl">
        <div class="guide-content">
          @foreach($guide_sections as $section)
            @php
              $tocText = $section['toc_text'] ?? '';
              $anchorId = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tocText)));
              $sectionContent = $section['section_content'] ?? '';
            @endphp

            <section id="{{ $anchorId }}" class="guide-section mb-12 last:mb-0">
              <div class="prose max-w-none guide-section-content">
                {!! $sectionContent !!}
              </div>
            </section>
          @endforeach
        </div>
      </main>
    </div>
  </div>
@endif

{{-- JavaScript for Smooth Scrolling --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Smooth scrolling for TOC links
  const tocLinks = document.querySelectorAll('.toc-link');
  tocLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);
      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
});
</script>