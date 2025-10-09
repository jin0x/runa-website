@php
  use App\Enums\ThemeVariant;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;

  // Background color classes based on theme
  $backgroundClasses = match ($theme) {
      ThemeVariant::PURPLE => 'bg-secondary-purple',
      ThemeVariant::CYAN => 'bg-secondary-cyan',
      ThemeVariant::YELLOW => 'bg-primary-yellow',
      ThemeVariant::GREEN => 'bg-primary-green-soft',
      default => 'bg-secondary-cyan',
  };

  // Text color for content (always dark on colored backgrounds)
  $textColor = TextColor::LIGHT;
@endphp

<div class="{{ $backgroundClasses }} p-8 md:p-12 flex flex-col justify-center {{ $attributes->get('class') }}">
  @if($heading)
    <x-heading
      :as="HeadingTag::H1"
      :size="HeadingSize::H1"
      :color="$textColor"
      class="mb-6"
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
              <path d="M18.002 8.53955L9.34766 17.1938L4.75586 12.6021L5.81641 11.5415L9.34766 15.0728L16.9414 7.479L18.002 8.53955Z" fill="black"/>
              <defs>
              <linearGradient id="paint0_linear_2793_21186" x1="0" y1="24" x2="24" y2="0" gradientUnits="userSpaceOnUse">
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
            :size="TextSize::XSMALL"
            :color="$textColor"
            class="{{ $fontWeight }}"
          >
            {{ $feature['feature_text'] }}
          </x-text>
        </li>
      @endforeach
    </ul>
  @endif

  {{-- Additional content slot --}}
  {{ $slot }}
</div>
