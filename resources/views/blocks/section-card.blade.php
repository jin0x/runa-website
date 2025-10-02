@php
  use App\Enums\SectionSize;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Handle image URL
  $image_url = '';
  if (!empty($image) && is_array($image)) {
      $image_url = $image['url'] ?? '';
      $image_alt = $image['alt'] ?? $heading;
  }

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
    <x-section-card
      :theme="$themeVariant"
      :heading="$heading"
      :description="$description"
      :features="$features"
      class="{{ $contentOrder }}"
    />
  </div>
</x-section>