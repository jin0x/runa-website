@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\TextColor;
  use App\Helpers\EnumHelper;
@endphp

@props([
    'featured' => false,
    'post' => null,
    'cardColor' => 'purple',
    'showLogo' => true,
    'showRating' => true,
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
  $rating = get_field('rating', $postId);
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
  $textColor = 'text-primary-dark';
@endphp

<article class="{{ $featured ? 'featured-testimonial-card' : 'testimonial-card' }} group">
  <div class="{{ $featured ? 'p-8 lg:p-12' : 'py-6 px-12' }} {{ $cardBgClasses }} rounded-2xl min-h-full flex gap-6 flex-1">
    <div>

      {{-- Header with Logo and Company Info --}}
      <div class="flex items-start justify-between mb-6">
        <div class="flex-1 min-w-0">
          @if($companyLogo && !$featured)
            <div class="flex items-center justify-start">
              <img
                src="{{ $companyLogo['sizes']['thumbnail'] ?? $companyLogo['url'] }}"
                alt="{{ $companyName }} logo"
                class="h-12 w-auto max-w-full object-contain"
              >
            </div>
          @endif

          @if($companyName && !$companyLogo)
            {{-- Company Name as link if URL provided --}}
            <x-heading
              :as="HeadingTag::H3"
              :size="$featured ? HeadingSize::H4 : HeadingSize::H5"
              class="text-neutral-900 mb-1"
            >
              @if($websiteUrl)
                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener"  class="hover:text-primary-green-neon transition-colors">
                  {{ $companyName }}
                </a>
              @else
                {{ $companyName }}
              @endif
            </x-heading>
          @endif
        </div>

        {{-- Rating Stars --}}
        @if($rating)
          <div class="flex items-center gap-1 ml-4">
            @for($i = 1; $i <= 5; $i++)
              <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' :  'text-neutral-300' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3. 462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3. 921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1. 838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.  38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            @endfor
          </div>
        @endif
      </div>

      {{-- Quote --}}
      @if($quote)
        <div class="flex-1 mb-6 min-h-32">
          <x-heading :as="HeadingTag::H3" :size="HeadingSize::H3" class="{{ $featured ?   'text-xl' : 'text-lg' }} text-primary-dark leading-relaxed">
            <strong>
              "{{ $featured ? $quote : $truncatedQuote }}"
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
              :color="TextColor::GRAY">
              {{ $clientPosition }}
            </x-text>
          @endif

          @if($testimonialDate)
            <div class="text-xs text-neutral-400 mt-2">
              {{ date('F j, Y', strtotime($testimonialDate)) }}
            </div>
          @endif
        </div>

        {{-- Read More Link for non-featured cards --}}
          @if($testimonialUrl)
          <div class="mt-4">
            <x-text 
              href="{{ $testimonialUrl }}"
              :as="TextTag::A" 
              :size="TextSize::XSMALL" 
              :color="TextColor::LIGHT"
              class="inline-flex items-center gap-1 !no-underline hover:underline transition-all duration-200 ease-in-out">
                <span>Read more</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="pt-1">
                  <path d="M7.00016 0.663574L5.82516 1.83857L10.4752 6.49691H0.333496V8.16357H10.4752L5.82516 12.8219L7.00016 13.9969L13.6668 7.33024L7.00016 0.663574Z" fill="black"/>
                </svg>
            </x-text>
          </div>
          @endif
      </div>
    </div>
    @if($companyLogo && $featured)
       <div class="flex items-center justify-center min-w-1/2">
         <img
           src="{{ $companyLogo['sizes']['thumbnail'] ?? $companyLogo['url'] }}"
           alt="{{ $companyName }} logo"
           class="h-24 w-auto max-w-full object-contain"
         >
       </div>
     @endif
  </div>
</article>
