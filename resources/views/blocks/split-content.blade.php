@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;

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
      'yellow' => 'bg-primary-yellow',
      'pink' => 'bg-secondary-pink',
      default => 'bg-secondary-cyan',
  };

  // Text color for content (always dark on colored backgrounds)
  $textColor = TextColor::DARK;

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
          :color="$textColor"
          class="mb-8"
        >
          {{ $heading }}
        </x-heading>
      @endif

      @if($description)
        <x-text
          :as="TextTag::P"
          :size="TextSize::BASE"
          :color="$textColor"
          class="mb-6"
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
              <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect width="24" height="24" rx="12" fill="url(#paint0_linear_2793_21186)"/>
                  <path d="M18.002 8.53955L9.34766 17.1938L4.75586 12.6021L5.81641 11.5415L9.34766 15.0728L16.9414                  7.479L18.002 8.53955Z" fill="black"/>
                  <defs>
                  <linearGradient id="paint0_linear_2793_21186" x1="0" y1="24" x2="24" y2="0"                   gradientUnits="userSpaceOnUse">
                  <stop stop-color="#00FFA3"/>
                  <stop offset="0.48313" stop-color="#93FF82"/>
                  <stop offset="0.943979" stop-color="#EEFC51"/>
                  </linearGradient>
                  </defs>
                  </svg>
              </div>
              
              {{-- Feature Text --}}
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::BASE"
                :color="$textColor"
                class="{{ $fontWeight }}"
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