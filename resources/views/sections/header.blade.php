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
    $has_primary_cta = !empty($primary_cta_url) && !empty($primary_cta_label);
    $has_secondary_cta = !empty($secondary_cta_url) && !empty($secondary_cta_label);
    $header_logo = get_field('header_logo', 'option');
    $header_logo_secondary = get_field('header_logo_secondary', 'option');
    $use_secondary_logo = is_singular('post') || is_singular('case-study');
    $active_header_logo = ($use_secondary_logo && $header_logo_secondary) ? $header_logo_secondary : $header_logo;
    $active_header_logo_alt = $siteName;
    if (is_array($active_header_logo) && !empty($active_header_logo['alt'])) {
      $active_header_logo_alt = $active_header_logo['alt'];
    }
@endphp
@if($enable_header_banner && $header_banner_message)
  <div id="header-banner" x-data="{ isVisible: true }" x-show="isVisible" class="hidden lg:flex justify-between absolute top-0 w-full bg-primary-violet text-white py-6 px-4 lg:px-12  items-center gap-4 z-[110]">
    <div>{!! $header_banner_message !!}</div>
    <button
      type="button"
      class="text-white bg-transparent border-2 border-white rounded-full h-6 w-6 cursor-pointer text-xs font-normal"
      @click="isVisible = false"
    >
      &#x2716;
    </button>
  </div>
@endif
@if($enable_header_banner && $header_banner_message)
  <div class="{{ is_admin_bar_showing() ? 'xl:pt-[40px]' : 'xl:pt-[60px]' }}">
    @endif
    <header class="absolute z-[100] w-full" role="banner">
      <div class="container mx-auto relative pt-6">
        <div class="mx-auto w-full px-4 xl:px-12 hidden xl:block">
          <div class="relative flex items-center justify-between sm:h-10 xl:justify-center" aria-label="Global">
            @if($active_header_logo)
              <div class="flex items-center flex-1 xl:absolute xl:inset-y-0 xl:left-0">
                <a href="{{ App\Helpers\get_frontend_home_url() }}">
                  <span class="sr-only">{{ $siteName }}</span>
                  <img src="{{ $active_header_logo['url'] }}" alt="{{ $active_header_logo_alt }}" class="w-31 h-auto">
                </a>
              </div>
            @endif
            @include('partials.navigation', [
                'menu' => 'primary_navigation',
                'name' => 'Primary Navigation',
                'slug' => 'primary',
            ])

            <div class="hidden xl:absolute xl:flex xl:items-center xl:justify-end xl:inset-y-0 xl:right-0 gap-2">
              @if ($has_primary_cta)
                <div class="inline-flex items-center {{ $has_secondary_cta ? 'gap-2' : '' }} rounded-full btn-nav transition-all duration-300 px-6 py-3 xl:min-h-[55px] overflow-hidden" style="background: var(--gradient-3);">
                  {{-- Primary CTA --}}
                  <a href="{{ $primary_cta_url }}" target="{{ $primary_cta_target }}" class="flex items-center hover:opacity-80 transition-opacity text-small text-white !no-underline {{ $has_secondary_cta ? 'gap-3' : 'justify-center w-full text-center' }}">
                    {{ $primary_cta_label }}
                    @if ($has_secondary_cta)
                      {{-- Divider --}}
                      <svg width="1" height="32" viewBox="0 0 1 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="0.5" y1="0" x2="0.5" y2="32" stroke="url(#dividerGradient)" stroke-width="1"/>
                        <defs>
                          <linearGradient id="dividerGradient" x1="0.5" y1="0" x2="0.5" y2="32" gradientUnits="userSpaceOnUse">
                            <stop offset="0.1634" stop-color="white" stop-opacity="0.1"/>
                            <stop offset="1" stop-color="white" stop-opacity="0.4"/>
                          </linearGradient>
                        </defs>
                      </svg>
                    @endif
                  </a>

                  @if ($has_secondary_cta)
                    {{-- Secondary CTA --}}
                    <a href="{{ $secondary_cta_url }}" target="{{ $secondary_cta_target }}" class="text-small text-gradient-primary hover:opacity-80 transition-opacity !no-underline">
                      {{ $secondary_cta_label }}
                    </a>
                  @endif
                </div>
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
          x-data="{
            isMobileNavOpen: false,
            adminBarHeight: null,
            // hamburguer validation color
            burgerBaseColor: '{{ $use_secondary_logo ? '#000' : '#fff' }}',
            burgerColor() {
              return this.isMobileNavOpen ? '#fff' : this.burgerBaseColor;
            },
            // logo swap when menu opens on dark overlay
            defaultLogoUrl: '{{ addslashes($active_header_logo['url'] ?? '') }}',
            defaultLogoAlt: '{{ addslashes($active_header_logo_alt ?? $siteName) }}',
            openLogoUrl: '{{ addslashes($use_secondary_logo ? ($header_logo['url'] ?? ($active_header_logo['url'] ?? '')) : ($active_header_logo['url'] ?? '')) }}',
            openLogoAlt: '{{ addslashes($use_secondary_logo ? ($header_logo['alt'] ?? $siteName) : ($active_header_logo_alt ?? $siteName)) }}'
          }"
          x-init="
            if (document.body.classList.contains('admin-bar')) {
              const setOffset = () => {
                adminBarHeight = (window.innerWidth < 783 ? 46 : 32) + 'px';
                if (isMobileNavOpen) {
                  $el.style.top = adminBarHeight;
                }
              };
              setOffset();
              window.addEventListener('resize', setOffset);
            }
          "
          x-effect="
            if (isMobileNavOpen && adminBarHeight) {
              $el.style.top = adminBarHeight;
            } else {
              $el.style.top = null;
            }
            document.body.classList.toggle('overflow-hidden', isMobileNavOpen);
          "
          class="xl:hidden flex flex-col"
          :class="isMobileNavOpen ? 'fixed inset-0 z-[120] bg-primary-dark-95 min-h-screen' : 'absolute top-0 inset-x-0 origin-top-right'">
          <div>
            <div class="px-6 py-3 flex items-center justify-between">
              @if($active_header_logo)
                <div>
                  <a href="{{ App\Helpers\get_frontend_home_url() }}">
                    <span class="sr-only">{{ $siteName }}</span>
                    <img
                      :src="isMobileNavOpen && openLogoUrl ? openLogoUrl : defaultLogoUrl"
                      :alt="isMobileNavOpen && openLogoAlt ? openLogoAlt : defaultLogoAlt"
                      class=" w-[100px] md:w-48 h-auto"
                    >
                  </a>
                </div>
              @endif
              <div class="-mr-2">
                <button type="button"
                        class="inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                        aria-label="Main menu"
                        x-bind:aria-expanded="isMobileNavOpen"
                        x-bind:aria-label="isMobileNavOpen ? 'Close main menu' : 'Main menu'"
                        x-on:click.prevent="isMobileNavOpen = !isMobileNavOpen"
                >
                  <span class="sr-only" x-text="isMobileNavOpen ? 'Close Menu' : 'Open Menu'"></span>
                  <svg :class="{ 'hidden': isMobileNavOpen }" class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24" :stroke="burgerColor()" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                  </svg>
                  <svg :class="{ 'hidden': !isMobileNavOpen }" class="h-10 w-10" xmlns="http://www.w3.org/2000/svg"
                       fill="none" viewBox="0 0 24 24" :stroke="burgerColor()" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </div>
            <div
              :class="{ 'hidden': !isMobileNavOpen }"
              class="transition transform flex-1 overflow-y-auto px-5 pb-8"
              style="max-height: 100vh;"
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
