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

  // Set background color based on theme
  $bgColor = match ($theme) {
      'light' => 'bg-white',
      default => 'bg-primary-dark',
  };

  // Set text color based on theme
  $textColor = $theme === 'dark' ? 'text-white' : 'text-primary-black';
  $eyebrowColor = $theme === 'dark' ? 'text-primary-green-neon' : 'text-primary-green-neon';
  $subtitleColor = $theme === 'dark' ? 'text-neutral-300' : 'text-neutral-600';

  // Grid classes based on card count
  $cardCount = count($pricing_cards);
  $gridClasses = match ($cardCount) {
      1 => 'grid-cols-1 max-w-md mx-auto',
      2 => 'grid-cols-1 md:grid-cols-2 max-w-4xl mx-auto',
      4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
      default => 'grid-cols-1 md:grid-cols-3',
  };
@endphp

<section class="{{ $bgColor }} {{ $block->classes }}">
  {{-- Title Section --}}
  <div class="py-16 px-4 lg:px-16 text-center">
    <div class="max-w-[1100px] mx-auto">
      {{-- Eyebrow --}}
      @if($eyebrow)
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::SMALL"
          class="block {{ $eyebrowColor }} mb-6 uppercase tracking-wider font-medium"
        >
          {{ $eyebrow }}
        </x-text>
      @endif

      {{-- Main Heading --}}
      @if($heading)
        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H2"
          class="{{ $textColor }} mb-6"
        >
          {{ $heading }}
        </x-heading>
      @endif

      {{-- Subtitle --}}
      @if($subtitle)
        <x-text
          :as="TextTag::P"
          :size="TextSize::BASE"
          class="{{ $subtitleColor }} max-w-4xl mx-auto"
        >
          {{ $subtitle }}
        </x-text>
      @endif
    </div>
  </div>

  {{-- Cards Section --}}
  @if(!empty($pricing_cards))
    <div class="px-4 lg:px-16 pb-16 bg-primary-dark">
      <div class="grid {{ $gridClasses }} gap-6 max-w-7xl mx-auto items-start">
        @foreach($pricing_cards as $card)
          <x-pricing-card
            :icon="$card['icon'] ?? null"
            :title="$card['title'] ?? ''"
            :description="$card['description'] ?? ''"
            :pricing_items="$card['pricing_items'] ?? []"
            :cta="$card['cta'] ?? null"
            :features_title="$card['features_title'] ?? ''"
            :features="$card['features'] ?? []"
            :asterisk_note="$card['asterisk_note'] ?? ''"
            :is_popular="$card['is_popular'] ?? false"
          />
        @endforeach
      </div>
    </div>
  @endif
</section>