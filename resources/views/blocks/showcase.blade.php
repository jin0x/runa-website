@php
  use App\Enums\SectionSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\ButtonVariant;

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
      default => 'bg-black',
  };

  // Set text colors based on theme
  $textColor = $theme === 'dark' ? 'text-gradient-primary' : 'text-primary-black';
  $eyebrowColor = $theme === 'dark' ? 'text-primary-green-soft' : 'text-neutral-600';

  // Map accent colors to CSS classes
  $accentClasses = match ($accent_color) {
      'green-soft' => 'text-primary-green-soft',
      'yellow' => 'text-primary-yellow',
      'pink' => 'text-secondary-pink',
      'purple' => 'text-secondary-purple',
      'cyan' => 'text-secondary-cyan',
      default => 'text-primary-green-neon',
  };

  // Button variant based on accent color
  $buttonVariant = match ($accent_color) {
      'green-soft' => ButtonVariant::PRIMARY,
      default => ButtonVariant::PRIMARY,
  };

  // Handle media URL
  $media_url = '';
  if ($media_type === 'video' && !empty($video) && is_array($video)) {
      $media_url = $video['url'] ?? '';
  } elseif ($media_type === 'image' && !empty($image)) {
      if (is_array($image)) {
          $media_url = $image['url'] ?? '';
      } else {
          $media_url = $image;
      }
  } elseif ($media_type === 'lottie' && !empty($lottie) && is_array($lottie)) {
      $media_url = $lottie['url'] ?? '';
  }
@endphp

<x-section :size="$sectionSizeValue" classes="py-16 px-16 lg:px-16 {{ $bgColor }} {{ $block->classes }}">
  <div class="text-center">
    <div class="flex flex-col items-center">

      {{-- Eyebrow --}}
      @if($eyebrow)
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::BASE"
          class="block uppercase tracking-wider {{ $eyebrowColor }} mb-8"
        >
          {{ $eyebrow }}
        </x-text>
      @endif

      {{-- Main Heading --}}
      @if($heading)
        <x-heading
          :as="HeadingTag::H2"
          :size="HeadingSize::H1"
          class="mb-12 {{ $textColor }} max-w-5xl"
        >
          {!! preg_replace('/\b(Fund|Pay|Own)\b/', '<span class="' .  $accentClasses . '">$1</span>', $heading) !!}
        </x-heading>
      @endif
    </div>

    {{-- Statistics Cards --}}
    @if(!empty($statistics_cards))
      <div class="grid grid-cols-2 md:grid-cols-4 p-6 mb-12">
        @foreach($statistics_cards as $card)
          <div class="text-center">
            @php
              $card_image = $card['icon'] ?? null;
              $card_link = $card['link'] ?? null;
              $alt_text = $card['alt_text'] ?? ($card_image['alt'] ?? 'Client icon');
              $card_url = $card_image['url'] ?? '';
            @endphp

            {{-- Icon --}}
            @if(!empty($card['icon']))
              <div class="mb-4 flex justify-center">
                <img 
                  src="{{ $card_url }}" 
                  alt="{{ $alt_text ?? 'Statistic icon' }}"
                  class="w-12 h-12 object-contain"
                />
              </div>
            @endif

            {{-- Statistic --}}
            @if(!empty($card['statistic']))
              <x-heading
                :as="HeadingTag::H3"
                :size="HeadingSize::H2"
                class="mb-2 text-primary-green-soft"
              >
                {{ $card['statistic'] }}
              </x-heading>
            @endif

            {{-- Description --}}
            @if(!empty($card['description']))
              <x-text
                :as="TextTag::P"
                :size="TextSize::SMALL"
                class="text-white"
              >
                {{ $card['description'] }}
              </x-text>
            @endif
          </div>
        @endforeach
      </div>
    @endif

    {{-- Media Section --}}
    @if(!empty($media_url))
      <div class="mb-12 flex justify-center">
        <div class="w-full ">
          <x-media
            :mediaType="$media_type"
            :mediaUrl="$media_url"
            :altText="$heading ?? 'Showcase media'"
            classes="w-full h-auto min-h-6xl object-contain"
          />
        </div>
      </div>
    @endif

    {{-- Call to Action --}}
    @if(!empty($cta) && !empty($cta['url']) && !empty($cta['title']))
      <div class="flex justify-center">
        <x-button
          :variant="$buttonVariant"
          :href="$cta['url']"
          target="{{ $cta['target'] ?? '_self' }}"
        >
          {{ $cta['title'] }}
        </x-button>
      </div>
    @endif
  </div>
</x-section>