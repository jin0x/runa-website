@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;

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

  // Handle image URL
  $image_url = '';
  if (!empty($image) && is_array($image)) {
      $image_url = $image['url'] ?? '';
      $image_alt = $image['alt'] ?? $heading;
  }

  // Background color classes
  $backgroundClasses = match ($background_color) {
      'green' => 'bg-primary-green-soft',
      default => 'bg-secondary-cyan',
  };

  // Icon color classes (opposite of background for contrast)
  $iconClasses = match ($background_color) {
      'green' => 'text-secondary-cyan',
      default => 'text-primary-green-soft',
  };

  // Layout order classes
  $imageOrder = $reverse_layout ? 'order-1 md:order-2' : 'order-1 md:order-1';
  $contentOrder = $reverse_layout ? 'order-2 md:order-1' : 'order-2 md:order-2';
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $block->classes }}">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-0 overflow-hidden rounded-[2rem]">
    
    {{-- Image Section --}}
    <div class="{{ $imageOrder }}">
      @if(!empty($image_url))
        <img 
          src="{{ $image_url }}" 
          alt="{{ $image_alt }}"
          class="w-full h-full object-cover min-h-[400px] md:min-h-[500px]"
        />
      @else
        <div class="w-full h-full bg-neutral-200 min-h-[400px] md:min-h-[500px] flex items-center justify-center">
          <span class="text-neutral-400">Image placeholder</span>
        </div>
      @endif
    </div>

    {{-- Content Section --}}
    <div class="{{ $contentOrder }} {{ $backgroundClasses }} p-8 md:p-12 flex flex-col justify-center">
      @if($heading)
        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H2"
          class="text-primary-black mb-8"
        >
          {{ $heading }}
        </x-heading>
      @endif

      @if($description)
        <x-text
          :as="TextTag::P"
          :size="TextSize::BASE"
          class="text-primary-black mb-6"
        >
          {{ $description }}
        </x-text>
      @endif

      @if(!empty($features))
        <ul class="space-y-4">
          @foreach($features as $feature)
            @php
              // Get font weight class based on feature style
              $fontWeight = match($feature['feature_style'] ?? 'normal') {
                  'bold' => 'font-bold',
                  default => 'font-normal',
              };
            @endphp

            <li class="flex items-center gap-2">
              {{-- Checkmark Icon --}}
              <div class="flex-shrink-0 w-6 h-6 {{ $iconClasses }} flex items-center justify-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
              </div>
              
              {{-- Feature Text --}}
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::BASE"
                class="text-primary-black {{ $fontWeight }}"
              >
                {{ $feature['feature_text'] }}
              </x-text>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
</x-section>