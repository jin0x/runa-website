@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;
  use App\Enums\TextColor;
  use App\Enums\ThemeVariant;
  use App\Helpers\EnumHelper;

  // Convert section_size string to SectionSize enum
  $sectionSizeValue = EnumHelper::getSectionSize($section_size);

  // Convert theme string to ThemeVariant enum
  $themeVariant = EnumHelper::getThemeVariant($theme);

  // Set text color based on theme
  $textColor = $themeVariant === ThemeVariant::DARK ? TextColor::LIGHT : TextColor::DARK;

  // Map card background colors to CSS classes
  $cardBgClasses = match ($card_background_color) {
      'green-neon' => 'bg-primary-green-neon',
      'green-soft' => 'bg-primary-green-soft',
      'cyan' => 'bg-secondary-cyan',
      'white' => 'bg-white',
      default => 'bg-primary-yellow',
  };

  // Button variant based on card background
  $buttonVariant = match ($card_background_color) {
      'white' => ButtonVariant::PRIMARY,
      default => ButtonVariant::PRIMARY,
  };

  // Text color for cards (most backgrounds need dark text)
  $cardTextColor = match ($card_background_color) {
      'white' => TextColor::DARK,
      default => TextColor::DARK,
  };

  // Determine grid columns based on card count
  $cardCount = count($cards);
  $gridClasses = match ($cardCount) {
      1 => 'grid-cols-1 max-w-md mx-auto',
      2 => 'grid-cols-1 md:grid-cols-2',
      3 => 'grid-cols-1 md:grid-cols-3',
      default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
  };
@endphp

<x-section :size="$sectionSizeValue" :variant="$themeVariant" classes="{{ $block->classes }}">
  <x-container>
    <div class="text-center">
      {{-- Section Heading --}}
      @if($heading)
        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H2"
          :color="$textColor"
          class="mb-12"
        >
          {{ $heading }}
        </x-heading>
      @endif

      {{-- Action Cards --}}
      @if(!empty($cards))
        <div class="grid {{ $gridClasses }} gap-6">
          @foreach($cards as $card)
            <div class="{{ $cardBgClasses }} rounded-2xl p-6">
            {{-- Icon --}}
              @if(!empty($card['icon']))
                <div class="mb-6">
                  <div class="w-20 h-20 bg-gradient-primary rounded-full flex items-center justify-center">
                    <img
                      src="{{ $card['icon']['url'] }}"
                      alt="{{ $card['icon']['alt'] ?? 'Card icon' }}"
                      class="w-12 h-12 object-contain"
                    />
                  </div>
                </div>
              @endif

              {{-- Card Title --}}
              @if(!empty($card['title']))
                <x-heading
                  :as="HeadingTag::H3"
                  :size="HeadingSize::H4"
                  :color="$cardTextColor"
                  class="mb-8 text-left"
                >
                  {{ $card['title'] }}
                </x-heading>
              @endif

              {{-- Optional Description --}}
              @if(!empty($card['description']))
                <x-text
                  :as="TextTag::P"
                  :size="TextSize::BASE"
                  :color="$cardTextColor"
                  class="mb-8 text-left"
                >
                  {{ $card['description'] }}
                </x-text>
              @endif

              {{-- CTA Button --}}
              @if(!empty($card['cta']) && !empty($card['cta']['url']) && !empty($card['cta']['title']))
                <div class="text-left">
                  <x-button
                    :variant="$buttonVariant"
                    :href="$card['cta']['url']"
                    target="{{ $card['cta']['target'] ?? '_self' }}"
                  >
                    {{ $card['cta']['title'] }}
                  </x-button>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </x-container>
</x-section>