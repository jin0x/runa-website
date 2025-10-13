@php
  use App\Enums\ButtonSize;
  use App\Enums\ButtonVariant;

    $enable_header_banner = get_field( 'enable_header_banner', 'option' );
    $header_banner_message = get_field( 'header_banner_message', 'option' );

    $header_buttons = get_field('header_buttons', 'option') ?: [];

    // Set primary and secondary buttons based on their position in the repeater
    $primary_cta = $header_buttons[0]['cta'] ?? [];
    $primary_cta_url = $primary_cta['url'] ?? '';
    $primary_cta_target = $primary_cta['target'] ?? '';
    $primary_cta_label = $primary_cta['title'] ?? '';

    $secondary_cta = $header_buttons[1]['cta'] ?? [];
    $secondary_cta_url = $secondary_cta['url'] ?? '';
    $secondary_cta_target = $secondary_cta['target'] ?? '';
    $secondary_cta_label = $secondary_cta['title'] ?? '';

    $header_logo = get_field('header_logo', 'option');
@endphp

@if($enable_header_banner && $header_banner_message)
  <div id="header-banner" class="hidden lg:flex justify-between absolute top-0 w-full bg-primary-violet text-white py-6 px-4 lg:px-12  items-center gap-4 z-[110]">
    <div>{!! $header_banner_message !!}</div>
    <button
      type="button"
      class="text-white bg-transparent border-2 border-white rounded-full h-6 w-6 cursor-pointer text-xs font-normal"
      onclick="document.getElementById('header-banner').style.display='none'"
    >
      &#x2716;
    </button>
  </div>
@endif

@if($enable_header_banner && $header_banner_message)
  <div class="{{ is_admin_bar_showing() ? 'lg:pt-[40px]' : 'lg:pt-[60px]' }}">
    @endif

    <header class="absolute z-[100] w-full" role="banner">
      <div class="relative pt-6">

        <div class="mx-auto w-full px-4 lg:px-12 hidden lg:block">
          <div class="relative flex items-center justify-between sm:h-10 lg:justify-center" aria-label="Global">
            @if($header_logo)
              <div class="flex items-center flex-1 lg:absolute lg:inset-y-0 lg:left-0">
                <a href="{{ home_url('/') }}">
                  <span class="sr-only">{{ $siteName }}</span>
                  <img src="{{ $header_logo['url'] }}" alt="{{ $header_logo['alt'] ?: $siteName }}" class="w-48 h-auto">
                </a>
              </div>
            @endif

            @include('partials.navigation', [
                'menu' => 'primary_navigation',
                'name' => 'Primary Navigation',
                'slug' => 'primary',
            ])

            <div class="hidden lg:absolute lg:flex lg:items-center lg:justify-end lg:inset-y-0 lg:right-0 gap-2">
              @if (!empty($primary_cta_url) && !empty($primary_cta_label))
                <x-button
                  :size="ButtonSize::DEFAULT"
                  :variant="ButtonVariant::NAV"
                  :iconPosition="'left'"
                  :iconType="'user'"
                  :href="$primary_cta_url"
                  target="{{ $primary_cta_target }}"
                >
                  {{ $primary_cta_label }}
                </x-button>
              @endif
              @if (!empty($secondary_cta_url) && !empty($secondary_cta_label))
                <x-button
                  :variant="ButtonVariant::PRIMARY"
                  :href="$secondary_cta_url"
                  target="{{ $secondary_cta_target }}"
                >
                  {{ $secondary_cta_label }}
                </x-button>
              @endif
            </div>

          </div>
        </div>

        <!--
              Mobile menu, show/hide based on menu open state.

              Entering: "duration-150 ease-out"
                From: "opacity-0 scale-95"
                To: "opacity-100 scale-100"
              Leaving: "duration-100 ease-in"
                From: "opacity-100 scale-100"
                To: "opacity-0 scale-95"
            -->
        <div
          x-data="{isMobileNavOpen: false}"
          class="absolute top-0 inset-x-0 p-2 origin-top-right lg:hidden">
          <div class="rounded-lg shadow-md bg-primary-dark ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between">

              @if($header_logo)
                <div>
                  <a href="{{ home_url('/') }}">
                    <span class="sr-only">{{ $siteName }}</span>
                    <img src="{{ $header_logo['url'] }}" alt="{{ $header_logo['alt'] ?: $siteName }}" class="w-48 h-auto">
                  </a>
                </div>
              @endif

              <div class="-mr-2">
                <button type="button"
                        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-navy"
                        aria-label="Main menu"
                        x-bind:aria-expanded="isMobileNavOpen"
                        x-bind:aria-label="isMobileNavOpen ? 'Close main menu' : 'Main menu'"
                        x-on:click.prevent="isMobileNavOpen = !isMobileNavOpen"
                >
                  <span class="sr-only" x-text="isMobileNavOpen ? 'Close Menu' : 'Open Menu'"></span>

                  <svg :class="{ 'hidden': isMobileNavOpen }" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                  </svg>

                  <svg :class="{ 'hidden': !isMobileNavOpen }" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                       fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>

                </button>
              </div>
            </div>

            <div
              :class="{ 'hidden': !isMobileNavOpen }"
              class="transition transform"
              x-show="isMobileNavOpen"
              @click.away="isMobileNavOpen = false"
              x-transition:enter="duration-150 ease-out"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100"
              x-transition:leave="duration-100 ease-in"
              x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95"
            >

              @include('partials.mobile-navigation', [
                'menu' => 'primary_navigation',
                'name' => 'Primary Navigation Mobile',
                'slug' => 'primary-mobile',
              ])

              <div class="flex flex-col gap-2 p-4 text-center items-start">
                @if (!empty($primary_cta_url) && !empty($primary_cta_label))
                  <x-button
                    :variant="ButtonVariant::PRIMARY"
                    :href="$primary_cta_url"
                    target="{{ $primary_cta_target }}"
                  >
                    {{ $primary_cta_label }}
                  </x-button>
                @endif
                @if (!empty($secondary_cta_url) && !empty($secondary_cta_label))
                  <x-button
                    :variant="ButtonVariant::SECONDARY"
                    :href="$secondary_cta_url"
                    target="{{ $secondary_cta_target }}"
                  >
                    {{ $secondary_cta_label }}
                  </x-button>
                @endif
              </div>
            </div>

          </div>
        </div>

      </div>

    </header>
    @if($enable_header_banner && $header_banner_message)
  </div>
@endif
