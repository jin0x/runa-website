@php
  /**
   * FAQ
   */
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
  use App\Enums\ThemeVariant;
  use App\Enums\SectionSize;
  use App\Enums\TextColor;
  use function App\Helpers\apply_tailwind_classes_to_content;

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

  // FAQ text color
  $textColor = $theme === 'dark' ? TextColor::LIGHT : TextColor::DARK;
  $svgClasses  = $theme === 'dark' ? 'white' : 'black';

  // Generate unique ID for the accordion
  $faqId = 'faq-' . uniqid();

@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }} overflow-visible">

  @if($section_eyebrow || $section_title || $section_description)
    <x-section-heading
      :eyebrow="$section_eyebrow"
      :heading="$section_title"
      :subtitle="$section_description"
      :variant="$themeVariant"
      classes="mb-12"
    />
  @endif
  
<x-container classes="!px-0">
    <div class=" max-w-6xl mx-auto"
         x-data="faqAccordion"
    >
      @foreach($faq_items as $index => $item)
        <div class="mb-4 rounded-lg overflow-hidden bg-[linear-gradient(180deg,rgba(102,102,102,0.40)_0%,rgba(102,102,102,0.20)_100%)]">
          <button
            @click="toggle({{ $index }})"
            class="w-full text-left p-6 flex justify-between items-center focus:outline-none"
            :aria-expanded="isItemOpen({{ $index }})"
            aria-controls="{{ $faqId }}-{{ $index }}"
            id="{{ $faqId }}-heading-{{ $index }}"
          >
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::XSMALL"
              :color="$textColor"
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
              :color="$textColor"
            >
              {!! apply_tailwind_classes_to_content($item['answer']) !!}
            </x-text>
          </div>
        </div>
      @endforeach
    </div>
</x-container>
  
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
