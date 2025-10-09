@php
  /**
   * FAQ
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;
  use function App\Helpers\apply_tailwind_classes_to_content;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Convert to optimal section heading variant for contrast
  $sectionHeadingVariant = EnumHelper::getSectionHeadingVariant($themeVariant);

  // FAQ text color
  $svgClasses  = $themeVariant === ThemeVariant::DARK ? 'white' : 'black';

  // Generate unique ID for the accordion
  $faqId = 'faq-' . uniqid();

  // Handle bottom background image URL
  $bottom_bg_image_url = '';
  if (!empty($bottom_background_image) && is_array($bottom_background_image)) {
      $bottom_bg_image_url = $bottom_background_image['url'] ?? '';
  } elseif (!empty($bottom_background_image)) {
      $bottom_bg_image_url = $bottom_background_image;
  }

@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }} overflow-visible">
  <x-container>
    @if($section_eyebrow || $section_title || $section_description)
      <x-section-heading
        :eyebrow="$section_eyebrow"
        :heading="$section_title"
        :subtitle="$section_description"
        :variant="$sectionHeadingVariant"
        classes="mb-12"
      />
    @endif

    <div class=" max-w-6xl mx-auto"
        x-data="faqAccordion"
    >
      @foreach($faq_items as $index => $item)
        <div class="mb-1 rounded-lg overflow-hidden bg-[linear-gradient(180deg,rgba(102,102,102,0.40)_0%,rgba(102,102,102,0.20)_100%)]">
          <button
            @click="toggle({{ $index }})"
            class="w-full text-left p-6 flex justify-between items-center focus:outline-none"
            :aria-expanded="isItemOpen({{ $index }})"
            aria-controls="{{ $faqId }}-{{ $index }}"
            id="{{ $faqId }}-heading-{{ $index }}"
          >
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::SMALL"
              class="font-bold"
            >
              {{ $item['question'] }}
            </x-text>
            <span class=" transition-transform duration-200" :class="{'rotate-180 transform': isItemOpen({{ $index }})}">
              <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_308_323)">
                <path d="M19.0108 9.31072L11.9397 16.3818L4.86863 9.31072" stroke="{{$svgClasses}}" stroke-width="2"/>
                </g>
                <defs>
                <clipPath id="clip0_308_323">
                <rect width="24" height="24" fill="white" transform="translate(0 0.5)"/>
                </clipPath>
                </defs>
              </svg>
            </span>
          </button>

          <div
            x-show="isItemOpen({{ $index }})"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="px-6 pb-6"
            id="{{ $faqId }}-{{ $index }}"
            aria-labelledby="{{ $faqId }}-heading-{{ $index }}"
            {{ $item['initially_open'] ? '' : 'style="display: none;"' }}
          >
            <x-text
              :as="TextTag::DIV"
              :size="TextSize::BASE"
            >
              {!! apply_tailwind_classes_to_content($item['answer']) !!}
            </x-text>
          </div>
        </div>
      @endforeach
    </div>
  </x-container>

  {{-- Bottom Background Image - Full Width Breakout --}}
  @if(!empty($bottom_bg_image_url))
    <div class="w-screen relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] mt-8">
      <img 
        src="{{ $bottom_bg_image_url }}" 
        alt="FAQ background decoration"
        class="w-full h-auto object-cover"
      >
    </div>
  @endif

</x-section>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('faqAccordion', () => ({
      openItems: [
        @foreach($faq_items as $index => $item)
          @if($item['initially_open'])
          {{ $index }},
        @endif
        @endforeach
      ],

      toggle(index) {
        if (this.openItems.includes(index)) {
          this.openItems = this.openItems.filter(item => item !== index);
        } else {
          this.openItems = [index];
        }
      },

      isItemOpen(index) {
        return this.openItems.includes(index);
      }
    }));
  });
</script>
