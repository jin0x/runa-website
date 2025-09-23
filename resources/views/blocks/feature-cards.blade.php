@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;

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

  // Set background color based on theme
  $bgColor = match ($theme) {
      'dark' => 'bg-primary-black',
      default => 'bg-primary-green-neon',
  };

  // Set text color based on theme
  $textColor = $theme === 'dark' ? 'text-white' : 'text-primary-black';

  // Grid classes based on columns
  $gridClasses = match ($columns) {
      '1' => 'grid-cols-1 max-w-md mx-auto',
      '2' => 'grid-cols-1 md:grid-cols-2',
      '4' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
      default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
  };
@endphp

<x-section :size="$sectionSizeValue" classes="{{ $bgColor }} {{ $block->classes }}">
  <div class="text-center">
    {{-- Section Heading --}}
    @if($heading)
      <x-heading
        :as="HeadingTag::H2"
        :size="HeadingSize::H2"
        class="mb-12 {{ $textColor }}"
      >
        {{ $heading }}
      </x-heading>
    @endif

    {{-- Feature Cards Grid --}}
    @if(!empty($cards))
      <div class="grid {{ $gridClasses }} gap-6">
        @foreach($cards as $card)
          <x-feature-card
            :image="$card['image'] ?? null"
            :title="$card['title'] ?? ''"
            :description="$card['description'] ?? ''"
            :cta="$card['cta'] ?? null"
            :theme="$card_theme"
            :size="$card_size"
          />
        @endforeach
      </div>
    @endif
  </div>
</x-section>