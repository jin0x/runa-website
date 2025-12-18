@php
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\BadgeVariant;

  // Get social networks from options
  $social_networks_array = get_field('social_networks', 'option') ?: [];
  $github_url = '';
  $twitter_url = '';
  $linkedin_url = '';
  $x_url = '';

  foreach ($social_networks_array as $network) {
    $network_type = $network['network'] ?? '';
    $network_url = $network['url'] ?? '';

    if ($network_type === 'github') {
      $github_url = $network_url;
    } elseif ($network_type === 'twitter') {
      $twitter_url = $network_url;
    } elseif ($network_type === 'x') {
      $x_url = $network_url;
    } elseif ($network_type === 'linkedin') {
      $linkedin_url = $network_url;
    }
  }

  // Prepare media URL
  $media_url = '';
  if ($media_type === 'video') {
    $video = get_field('video');
    $media_url = (!empty($video) && is_array($video)) ? ($video['url'] ?? '') : '';
  } elseif ($media_type === 'image' && !empty($image)) {
    if (is_array($image)) {
      $media_url = $image['url'] ?? '';
    } else {
      $media_url = $image;
    }
  }
@endphp

<x-section classes="{{ $block->classes }}">
  <x-container>
    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-18">

      {{-- Left Column - Main Content --}}
      <div class="flex flex-col">
        <div>
          {{-- Badge --}}
          @if(!empty($badge))
          <x-badge :variant="BadgeVariant::TERTIARY" class="mb-6">
            {{ $badge }}
          </x-badge>
          @endif
        </div>

        {{-- Title - 64px (Hero Title) --}}
        @if(!empty($title))
          <x-heading
            :as="HeadingTag::H1"
            :size="HeadingSize::SUPER"
            class="mb-6"
          >
            {{ $title }}
          </x-heading>
        @endif

        {{-- List Items - 18px (Medium Body) --}}
        @if(!empty($list_items))
          <ul class="mb-12 space-y-4">
            @foreach($list_items as $item)
              <li class="flex gap-3 items-start">
                {{-- Bullet point --}}
                <span class="flex-shrink-0 w-1.5 h-1.5 rounded-full bg-primary-dark mt-2"></span>
                <x-text
                  :as="TextTag::SPAN"
                  :size="TextSize::MEDIUM"
                  class="flex-1"
                >
                  {{ $item['list_item_text'] }}
                </x-text>
              </li>
            @endforeach
          </ul>
        @endif

        {{-- Video Player --}}
        @if(!empty($media_url))
          <div class="w-full aspect-video">
            <x-media
              :mediaType="$media_type"
              :mediaUrl="$media_url"
              classes="w-full aspect-video"
              containerClasses="overflow-hidden rounded-lg"
            />
          </div>
        @endif
      </div>

      {{-- Right Sidebar --}}
      <div class="flex flex-col">

        {{-- Presenters Section --}}
        @if(!empty($presenters))
          <div>
            <x-heading
              :as="HeadingTag::H6"
              :size="HeadingSize::H6_BOLD"
              class="font-bold mb-8"
            >
              PRESENTERS
            </x-heading>

            <div class="space-y-8">
              @foreach($presenters as $presenter)
                <div class="flex gap-4 items-start">
                  @if(!empty($presenter['presenter_image']))
                    <img
                      src="{{ $presenter['presenter_image']['url'] }}"
                      alt="{{ $presenter['presenter_name'] }}"
                      class="w-16 h-16 rounded-full object-cover flex-shrink-0"
                    />
                  @endif
                  <div>
                    <x-heading
                      :as="HeadingTag::H4"
                      :size="HeadingSize::H4_BOLD"
                      class="mb-0"
                    >
                      {{ $presenter['presenter_name'] }}
                    </x-heading>
                    @if(!empty($presenter['presenter_position']))
                      <x-text
                        :as="TextTag::P"
                        :size="TextSize::BASE"
                        class="mt-1"
                      >
                        {{ $presenter['presenter_position'] }}
                      </x-text>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          {{-- Divider --}}
          <hr class="border-t my-12" style="border-color: var(--color-secondary-purple);" />
        @endif

        {{-- Newsletter Signup Section --}}
        <div class="mb-6">
          <x-text
            :as="TextTag::P"
            :size="TextSize::MEDIUM"
            class="font-bold mb-6"
          >
            Sign up for our newsletter
          </x-text>

          <form action="#" method="POST" class="flex gap-2 items-center">
            <?php wp_nonce_field('newsletter_signup', 'newsletter_nonce'); ?>
            <label for="webinar-newsletter-email" class="sr-only">Email address</label>
            <input
              type="email"
              id="webinar-newsletter-email"
              name="email"
              placeholder="Email address"
              required
              class="flex-1 px-4 py-3 bg-neutral-500 placeholder-white rounded-md focus:outline-none focus:ring-2 focus:ring-primary-lime focus:border-transparent max-h-10 text-sm"
              style="font-family: var(--font-body);"
            >
            <button
              type="submit"
              class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-[#00FFA3] via-[#93FF82] to-[#EEFC51] rounded-full flex items-center justify-center hover:opacity-90 transition-opacity"
              aria-label="Subscribe to newsletter"
            >
              <svg width="40" height="40" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_webinar_newsletter)">
                  <path d="M22.7891 16L16.8945 21.8945L15.834 20.834L19.917 16.75H9V15.25H19.917L15.834 11.166L16.8945 10.1055L22.7891 16Z" fill="black"/>
                </g>
                <defs>
                  <clipPath id="clip0_webinar_newsletter">
                    <rect width="20" height="20" fill="white" transform="translate(6 6)"/>
                  </clipPath>
                </defs>
              </svg>
            </button>
          </form>
        </div>

        {{-- Divider --}}
        <hr class="border-t my-12" style="border-color: var(--color-secondary-purple);" />

        {{-- Social Share Section --}}
        <div>
          <x-text
            :as="TextTag::P"
            :size="TextSize::MEDIUM"
            class="font-bold mb-2"
          >
            Share
          </x-text>

          @php
            $current_url = urlencode(get_permalink());
            $current_title = urlencode(get_the_title());
          @endphp

          <div class="flex gap-3">
            {{-- Twitter/X Share --}}
            <a href="https://twitter.com/intent/tweet?url={{ $current_url }}&text={{ $current_title }}" class="flex items-center gap-2 hover:opacity-70 transition-opacity" target="_blank" rel="noopener noreferrer">
              <svg class="h-5 w-5" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M13.0087 24H12.956C6.35573 24 0.985886 18.6286 0.985886 12.0264V11.9736C0.985886 5.37142 6.35573 0 12.956 0H13.0087C19.609 0 24.9788 5.37142 24.9788 11.9736V12.0264C24.9788 18.6286 19.609 24 13.0087 24ZM12.956 0.812375C6.80322 0.812375 1.79802 5.81904 1.79802 11.9736V12.0264C1.79802 18.181 6.80322 23.1876 12.956 23.1876H13.0087C19.1615 23.1876 24.1667 18.181 24.1667 12.0264V11.9736C24.1667 5.81904 19.1615 0.812375 13.0087 0.812375H12.956Z" fill="currentColor"/>
                <path d="M6.0933 5.65527L11.4388 12.8042L6.06 18.6167H7.2709L11.9805 13.528L15.7853 18.6167H19.9053L14.2593 11.0657L19.2661 5.65527H18.0552L13.7184 10.3419L10.2141 5.65527H6.0941H6.0933ZM7.87349 6.54728H9.76578L18.1235 17.7247H16.2312L7.87349 6.54728Z" fill="currentColor"/>
              </svg>
            </a>

            {{-- LinkedIn Share --}}
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $current_url }}" class="flex items-center gap-2 hover:opacity-70 transition-opacity" target="_blank" rel="noopener noreferrer">
              <svg class="h-5 w-5" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M13.0017 24H12.9489C6.34867 24 0.978821 18.6286 0.978821 12.0264V11.9736C0.978821 5.37143 6.34867 0 12.9489 0H13.0017C19.6019 0 24.9718 5.37143 24.9718 11.9736V12.0264C24.9718 18.6286 19.6019 24 13.0017 24ZM12.9489 0.812375C6.79616 0.812375 1.79096 5.81905 1.79096 11.9736V12.0264C1.79096 18.181 6.79616 23.1876 12.9489 23.1876H13.0017C19.1544 23.1876 24.1596 18.181 24.1596 12.0264V11.9736C24.1596 5.81905 19.1544 0.812375 13.0017 0.812375H12.9489Z" fill="currentColor"/>
                <path d="M6.91323 8.11337C6.60787 7.82985 6.45601 7.47891 6.45601 7.06135C6.45601 6.64379 6.60868 6.2774 6.91323 5.99307C7.21859 5.70955 7.61167 5.56738 8.09327 5.56738C8.57486 5.56738 8.95251 5.70955 9.25706 5.99307C9.56243 6.27659 9.71429 6.63323 9.71429 7.06135C9.71429 7.48947 9.56161 7.82985 9.25706 8.11337C8.9517 8.39689 8.56431 8.53906 8.09327 8.53906C7.62223 8.53906 7.21859 8.39689 6.91323 8.11337ZM9.45766 9.73975V18.4322H6.71182V9.73975H9.45766Z" fill="currentColor"/>
                <path d="M18.5981 10.5989C19.1966 11.2488 19.4955 12.1408 19.4955 13.2765V18.2791H16.8878V13.629C16.8878 13.0563 16.7391 12.6111 16.4427 12.2943C16.1463 11.9775 15.7467 11.8182 15.2464 11.8182C14.7462 11.8182 14.3466 11.9767 14.0501 12.2943C13.7537 12.6111 13.6051 13.0563 13.6051 13.629V18.2791H10.9819V9.71583H13.6051V10.8515C13.8707 10.473 14.2288 10.174 14.6787 9.95385C15.1287 9.7337 15.6346 9.62402 16.1974 9.62402C17.1996 9.62402 18.0004 9.94897 18.5981 10.5989Z" fill="currentColor"/>
              </svg>
            </a>

            {{-- Facebook Share --}}
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $current_url }}" class="flex items-center gap-2 hover:opacity-70 transition-opacity" target="_blank" rel="noopener noreferrer">
              <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12.0229 24H11.9701C5.36984 24 0 18.6286 0 12.0264V11.9736C0 5.37144 5.36984 0 11.9701 0H12.0229C18.6231 0 23.9929 5.37144 23.9929 11.9736V12.0264C23.9929 18.6286 18.6231 24 12.0229 24ZM11.9701 0.812375C5.81733 0.812375 0.812136 5.81906 0.812136 11.9736V12.0264C0.812136 18.181 5.81733 23.1876 11.9701 23.1876H12.0229C18.1756 23.1876 23.1808 18.181 23.1808 12.0264V11.9736C23.1808 5.81906 18.1756 0.812375 12.0229 0.812375H11.9701Z" fill="currentColor"/>
                <path d="M13.6104 9.30915V11.8348H16.7339L16.2393 15.2371H13.6104V23.0757C13.0834 23.1488 12.5441 23.187 11.9967 23.187C11.3649 23.187 10.7444 23.1366 10.1402 23.0391V15.2371H7.25953V11.8348H10.1402V8.74455C10.1402 6.82735 11.6938 5.27246 13.6112 5.27246V5.27409C13.6169 5.27409 13.6218 5.27246 13.6275 5.27246H16.7347V8.21488H14.7044C14.101 8.21488 13.6112 8.70474 13.6112 9.30834L13.6104 9.30915Z" fill="currentColor"/>
              </svg>
            </a>
          </div>
        </div>

      </div>
    </div>
  </x-container>
</x-section>
