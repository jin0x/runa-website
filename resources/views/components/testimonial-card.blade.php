@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'featured' => false,
    'post' => null,
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
  $rating = get_field('rating', $postId);
  $testimonialDate = get_field('testimonial_date', $postId);

  // Fallback to excerpt if quote is empty
  if (empty($quote)) {
    $quote = get_the_excerpt($postId);
  }

  // Limit quote length for cards
  $truncatedQuote = strlen($quote) > 150 ? substr($quote, 0, 150) . '...' : $quote;
@endphp

<article class="{{ $featured ? 'featured-testimonial-card' : 'testimonial-card' }} group">
  <div class="{{ $featured ? 'p-8 lg:p-12' : 'p-6' }} bg-white rounded-2xl border border-neutral-200 hover:border-neutral-300 transition-all duration-300 hover:shadow-lg h-full flex flex-col">

    {{-- Header with Logo and Company Info --}}
    <div class="flex items-start justify-between mb-6">
      <div class="flex-1">
        @if($companyLogo)
          <div class="mb-4 flex items-center justify-start">
            <img
              src="{{ $companyLogo['sizes']['thumbnail'] ?? $companyLogo['url'] }}"
              alt="{{ $companyName }} logo"
              class="h-12 w-auto object-contain"
            >
          </div>
        @endif

        @if($companyName)
          <x-heading
            :as="HeadingTag::H3"
            :size="$featured ? HeadingSize::H4 : HeadingSize::H5"
            class="text-neutral-900 mb-1"
          >
            @if($websiteUrl)
              <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" class="hover:text-primary-green-neon transition-colors">
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
            <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-neutral-300' }}" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          @endfor
        </div>
      @endif
    </div>

    {{-- Quote --}}
    @if($quote)
      <div class="flex-1 mb-6">
        <blockquote class="{{ $featured ? 'text-md' : 'text-xs' }} text-neutral-700 leading-relaxed">
          "{{ $featured ? $quote : $truncatedQuote }}"
        </blockquote>
      </div>
    @endif

    {{-- Client Info --}}
    <div class="mt-auto pt-4 border-t border-neutral-100">
      @if($clientName)
        <div class="text-sm font-medium text-neutral-900 mb-1">{{ $clientName }}</div>
      @endif

      @if($clientPosition)
        <div class="text-sm text-neutral-600">{{ $clientPosition }}</div>
      @endif

      @if($testimonialDate)
        <div class="text-xs text-neutral-400 mt-2">
          {{ date('F j, Y', strtotime($testimonialDate)) }}
        </div>
      @endif
    </div>

    {{-- Read More Link for non-featured cards --}}
    @if(!$featured && strlen($quote) > 150)
      <div class="mt-4">
        <a href="{{ $permalink }}" class="text-sm text-primary-green-neon hover:text-primary-green-dark transition-colors font-medium">
          Read full testimonial →
        </a>
      </div>
    @endif
  </div>
</article>
