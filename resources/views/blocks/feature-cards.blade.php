@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextColor;
  use App\Enums\ThemeVariant;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = match ($section_size) {
      'none' => SectionSize::NONE,
      'xs' => SectionSize::XSMALL,
      'sm' => SectionSize::SMALL,
      'md' => SectionSize::MEDIUM,
      'lg' => SectionSize::LARGE,
      'xl' => SectionSize::XLARGE,
      default => SectionSize::LARGE,
  };

  // Convert theme string to ThemeVariant enum
  $themeVariant = match ($theme) {
      'light' => ThemeVariant::LIGHT,
      'dark' => ThemeVariant::DARK,
      'green' => ThemeVariant::GREEN,
      'purple' => ThemeVariant::PURPLE,
      default => ThemeVariant::LIGHT,
  };

  // Set text color based on theme
  $textColor = $theme === 'dark' ? TextColor::LIGHT : TextColor::DARK;

  // Grid classes based on columns
  $gridClasses = match ($columns) {
      '1' => 'grid-cols-1 max-w-md mx-auto',
      '2' => 'grid-cols-1 md:grid-cols-2',
      '4' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
      default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
  };
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  <x-container classes="flex flex-col items-center">
    {{-- Section Heading --}}
    @if($heading)
      <x-heading
        :as="HeadingTag::H4"
        :size="HeadingSize::H4"
        :color="$textColor"
        class="text-center mb-12 max-w-3xl"
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
  </x-container>
</x-section>