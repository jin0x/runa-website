@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;
  use App\Enums\ThemeVariant;
@endphp

@props([
    'post' => null,
    'cardColor' => 'purple',
])

@php
  // Get testimonial data
  $postId = $post ?? get_the_ID();
  $title = get_the_title($postId);
  $permalink = get_permalink($postId);

  // Get ACF fields
  $companyName = get_field('company_name', $postId);
  $clientName = get_field('client_name', $postId);
  $clientPosition = get_field('client_position', $postId);
  $quote = get_field('quote', $postId);
  $companyLogo = get_field('company_logo', $postId);
  $websiteUrl = get_field('website_url', $postId);
  $testimonialUrl = get_field('testimonial_url', $postId);
  $testimonialDate = get_field('testimonial_date', $postId);

  // Fallback to excerpt if quote is empty
  if (empty($quote)) {
    $quote = get_the_excerpt($postId);
  }

  // Limit quote length for cards
  $truncatedQuote = strlen($quote) > 150 ? substr($quote, 0, 150) . '...' : $quote;

    // Map card background colors using EnumHelper
  $cardBgClasses = EnumHelper::getCardBackgroundClass($cardColor);

  // All cards use dark text on these bright backgrounds
  $positionTextColor = match ($cardColor) {
      ThemeVariant::PURPLE => TextColor::LIGHT,
      ThemeVariant::CYAN => TextColor::LIGHT,
      ThemeVariant::GREEN => TextColor::GRAY,
      ThemeVariant::YELLOW => TextColor::GRAY,
  };
  
@endphp

<article class="featured-testimonial-card group">
  <div class="p-8 lg:p-12 {{ $cardBgClasses }} rounded-2xl min-h-full flex gap-6 flex-1">
    <div>
      {{-- Quote --}}
      @if($quote)
        <div class="flex-1 mb-6 min-h-32">
          <x-heading :as="HeadingTag::H3" :size="HeadingSize::H3" class="text-xl text-primary-dark leading-relaxed indent-[-0.45em] pl-[0.45em]">
            <strong>
              "{{ $quote }}"
            </strong>
          </x-heading>
        </div>
      @endif

      {{-- Client Info --}}
      <div class="flex justify-between mt-auto pt-6 border-t border-primary-dark">
        <div class="flex flex-col gap-y-1">
          {{-- Client Name --}}
          @if($clientName)
            <x-text 
              :as="TextTag::P" 
              :size="TextSize::XSMALL" 
              :color="TextColor::LIGHT">  
              <strong>
                {{ $clientName }}
              </strong>
            </x-text>
          @endif

          @if($clientPosition)
            <x-text 
              :as="TextTag::P" 
              :size="TextSize::SMALL" 
              :color="$positionTextColor">
              {{ $clientPosition }}
            </x-text>
          @endif

          @if($testimonialDate)
            <div class="text-xs text-neutral-400 mt-2">
              {{ date('F j, Y', strtotime($testimonialDate)) }}
            </div>
          @endif
        </div>

        {{-- Read More Link --}}
          @if($testimonialUrl)
          <div class="mt-4">
            <x-text 
              href="{{ $testimonialUrl }}"
              :as="TextTag::A" 
              :size="TextSize::XSMALL" 
              :color="TextColor::LIGHT"
              class="inline-flex items-center gap-1 !no-underline hover:underline transition-all duration-200 ease-in-out">
                <span>Read more</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                  <path d="M7.00016 0.663574L5.82516 1.83857L10.4752 6.49691H0.333496V8.16357H10.4752L5.82516 12.8219L7.00016 13.9969L13.6668 7.33024L7.00016 0.663574Z" fill="black"/>
                </svg>
            </x-text>
          </div>
          @endif
      </div>
    </div>
    @if($companyLogo)
       <div class="flex items-center justify-center min-w-1/2">
          @if($websiteUrl)
            <a href="{{ $websiteUrl }}" target="_blank" rel="noopener">
              <img
                src="{{ $companyLogo['sizes']['thumbnail'] ?? $companyLogo['url'] }}"
                alt="{{ $companyName }} logo"
                class="h-24 w-auto max-w-full object-contain"
              >
            </a>
          @else
            <img
              src="{{ $companyLogo['sizes']['thumbnail'] ?? $companyLogo['url'] }}"
              alt="{{ $companyName }} logo"
              class="h-24 w-auto max-w-full object-contain"
            >
          @endif
       </div>
     @endif
  </div>
</article>
