<?php
use App\Enums\ButtonVariant;
use App\Enums\TextTag;
use App\Enums\TextSize;

$footer_logo = get_field('footer_logo', 'option');
$footer_copyrights = get_field('copyrights', 'option');

// Get footer buttons from the repeater
$footer_buttons = get_field('footer_buttons', 'option') ?: [];

// Set primary and secondary buttons based on their position in the repeater
$footer_primary_button = $footer_buttons[0]['cta'] ?? [];
$footer_primary_button_label = $footer_primary_button['title'] ?? '';
$footer_primary_button_link = $footer_primary_button['url'] ?? '';
$footer_primary_button_target = $footer_primary_button['target'] ?? false;

$footer_secondary_button = $footer_buttons[1]['cta'] ?? [];
$footer_secondary_button_label = $footer_secondary_button['title'] ?? '';
$footer_secondary_button_link = $footer_secondary_button['url'] ?? '';
$footer_secondary_button_target = $footer_secondary_button['target'] ?? false;

// Get social networks from the repeater
$social_networks_array = get_field('social_networks', 'option') ?: [];

// Initialize variables for social networks
$github_url = '';
$twitter_url = '';
$linkedin_url = '';

// Loop through social networks to find specific networks
foreach ($social_networks_array as $network) {
  $network_type = $network['network'] ?? '';
  $network_url = $network['url'] ?? '';

  if ($network_type === 'github') {
    $github_url = $network_url;
  } elseif ($network_type === 'twitter') {
    $twitter_url = $network_url;
  } elseif ($network_type === 'linkedin') {
    $linkedin_url = $network_url;
  }
}
?>

<footer class="footer bg-primary-dark text-gray-300 py-16 relative" aria-labelledby="footer-heading">
  <h2 id="footer-heading" class="sr-only">Footer</h2>
  <div class="container mx-auto px-4">
    <!-- Top Section: Logo and CTA Buttons -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-16">
      <!-- Logo -->
      <div class="mb-8 md:mb-0">
        @if($footer_logo)
          <a href="<?php echo home_url('/'); ?>">
            <img src="{{ $footer_logo['url'] }}" alt="{{ $footer_logo['alt'] }}">
          </a>
        @endif
      </div>


      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-4">
        @if(!empty($footer_primary_button_link) && !empty($footer_primary_button_label))
          <x-button
            :variant="ButtonVariant::PURPLE"
            :href="$footer_primary_button_link"
            target="{{ $footer_primary_button_target ? '_blank' : '_self' }}"
          >
            {{ $footer_primary_button_label }}
          </x-button>
        @endif

        @if(!empty($footer_secondary_button_link) && !empty($footer_secondary_button_label))
          <x-button
            :variant="ButtonVariant::GREEN"
            :href="$footer_secondary_button_link"
            target="{{ $footer_secondary_button_target ? '_blank' : '_self' }}"
          >
            {{ $footer_secondary_button_label }}
          </x-button>
        @endif
      </div>
    </div>

    <!-- Middle Section: Connect With Us and Pages -->
    <div class="flex flex-col md:flex-row gap-16 mb-16">
      <!-- Connect With Us / Social Media -->
      <div class="w-full md:w-1/2">
        <h3 class="text-white text-xl mb-6">Connect With Us</h3>
        <div class="flex flex-col gap-6">
          @if ( $github_url )
            <a href="<?php echo esc_url($github_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-6 w-6" width="24" height="25" viewBox="0 0 24 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2.27344C6.475 2.27344 2 6.74844 2 12.2734C1.99887 14.3727 2.65882 16.419 3.88622 18.1221C5.11362 19.8252 6.84615 21.0985 8.838 21.7614C9.338 21.8484 9.525 21.5484 9.525 21.2854C9.525 21.0484 9.512 20.2614 9.512 19.4234C7 19.8864 6.35 18.8114 6.15 18.2484C6.037 17.9604 5.55 17.0734 5.125 16.8354C4.775 16.6484 4.275 16.1854 5.112 16.1734C5.9 16.1604 6.462 16.8984 6.65 17.1984C7.55 18.7104 8.988 18.2854 9.562 18.0234C9.65 17.3734 9.912 16.9364 10.2 16.6864C7.975 16.4364 5.65 15.5734 5.65 11.7484C5.65 10.6604 6.037 9.76144 6.675 9.06044C6.575 8.81044 6.225 7.78544 6.775 6.41044C6.775 6.41044 7.612 6.14844 9.525 7.43644C10.3391 7.2105 11.1802 7.09678 12.025 7.09844C12.875 7.09844 13.725 7.21044 14.525 7.43544C16.437 6.13544 17.275 6.41144 17.275 6.41144C17.825 7.78644 17.475 8.81144 17.375 9.06144C18.012 9.76144 18.4 10.6484 18.4 11.7484C18.4 15.5864 16.063 16.4364 13.838 16.6864C14.2 16.9984 14.513 17.5984 14.513 18.5364C14.513 19.8734 14.5 20.9484 14.5 21.2864C14.5 21.5484 14.688 21.8604 15.188 21.7604C17.173 21.0902 18.8979 19.8144 20.1199 18.1126C21.3419 16.4107 21.9994 14.3686 22 12.2734C22 6.74844 17.525 2.27344 12 2.27344Z" fill="#F5F5F5"/>
              </svg>
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::CAPS"
                class=""
              >
                GitHub
              </x-text>
            </a>
          @endif

          @if ( $twitter_url )
            <a href="<?php echo esc_url($twitter_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-5 w-5" width="20" height="19" viewBox="0 0 20 19" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_1282_13595)">
                  <path d="M10 8.27444C9.09445 6.51333 6.62778 3.22999 4.33333 1.61333C2.13889 0.057772 1.3 0.329994 0.75 0.574439C0.116667 0.863327 0 1.84111 0 2.41333C0 2.98555 0.316667 7.12444 0.522222 7.81333C1.2 10.0911 3.61667 10.8633 5.83889 10.6189C5.95 10.6022 6.06667 10.5855 6.18333 10.5744C6.06667 10.5911 5.95556 10.6078 5.83889 10.6189C2.57778 11.1022 -0.316667 12.2911 3.48333 16.5189C7.66111 20.8467 9.20556 15.5911 10 12.93C10.7944 15.5911 11.7111 20.6578 16.4444 16.5189C20 12.93 17.4222 11.1022 14.1611 10.6189C14.0444 10.6078 13.9278 10.5911 13.8167 10.5744C13.9333 10.5911 14.05 10.6022 14.1611 10.6189C16.3833 10.8689 18.8 10.0967 19.4778 7.81333C19.6833 7.12444 20 2.99111 20 2.41333C20 1.83555 19.8833 0.863327 19.25 0.574439C18.7 0.324439 17.8611 0.057772 15.6667 1.60777C13.3722 3.22444 10.9056 6.50777 10 8.26888V8.27444Z" fill="#F5F5F5"/>
                </g>
                <defs>
                  <clipPath id="clip0_1282_13595">
                    <rect width="20" height="17.7778" fill="white" transform="translate(0 0.382812)"/>
                  </clipPath>
                </defs>
              </svg>
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::CAPS"
                class=""
              >
                Bluesky
              </x-text>
            </a>
          @endif

          @if ( $linkedin_url )
            <a href="<?php echo esc_url($linkedin_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-6 w-6" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 3.27344H20C20.2652 3.27344 20.5196 3.37879 20.7071 3.56633C20.8946 3.75387 21 4.00822 21 4.27344V20.2734C21 20.5387 20.8946 20.793 20.7071 20.9805C20.5196 21.1681 20.2652 21.2734 20 21.2734H4C3.73478 21.2734 3.48043 21.1681 3.29289 20.9805C3.10536 20.793 3 20.5387 3 20.2734V4.27344C3 4.00822 3.10536 3.75387 3.29289 3.56633C3.48043 3.37879 3.73478 3.27344 4 3.27344ZM6.988 14.3384C6.928 14.6054 6.898 14.8934 6.898 15.1284C6.898 16.0554 7.38 16.6704 8.406 16.6704C9.257 16.6704 9.947 16.1444 10.444 15.2954L10.141 16.5624H11.831L12.797 12.5314C13.038 11.5114 13.507 10.9814 14.216 10.9814C14.774 10.9814 15.121 11.3414 15.121 11.9384C15.121 12.1114 15.106 12.2994 15.046 12.5034L14.548 14.3564C14.4768 14.612 14.4411 14.8761 14.442 15.1414C14.442 16.0214 14.94 16.6644 15.982 16.6644C16.872 16.6644 17.582 16.0684 17.974 14.6394L17.31 14.3724C16.978 15.3304 16.69 15.5024 16.464 15.5024C16.238 15.5024 16.117 15.3464 16.117 15.0324C16.117 14.8914 16.147 14.7344 16.193 14.5454L16.676 12.7404C16.796 12.3164 16.842 11.9404 16.842 11.5954C16.842 10.2454 16.057 9.54044 15.106 9.54044C14.216 9.54044 13.31 10.3754 12.858 11.2554L13.189 9.67644H10.609L10.246 11.0664H11.454L10.71 14.1644C10.127 15.5144 9.054 15.5364 8.92 15.5044C8.698 15.4534 8.557 15.3654 8.557 15.0664C8.557 14.8944 8.587 14.6464 8.663 14.3484L9.795 9.67644H6.927L6.565 11.0664H7.757L6.987 14.3384H6.988ZM8.625 8.89844C8.92337 8.89844 9.20952 8.77991 9.4205 8.56893C9.63147 8.35795 9.75 8.07181 9.75 7.77344C9.75 7.47507 9.63147 7.18892 9.4205 6.97794C9.20952 6.76696 8.92337 6.64844 8.625 6.64844C8.32663 6.64844 8.04048 6.76696 7.8295 6.97794C7.61853 7.18892 7.5 7.47507 7.5 7.77344C7.5 8.07181 7.61853 8.35795 7.8295 8.56893C8.04048 8.77991 8.32663 8.89844 8.625 8.89844Z" fill="#F5F5F5"/>
              </svg>
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::CAPS"
                class=""
              >
                LinkedIn
              </x-text>
            </a>
          @endif
        </div>
      </div>

      <!-- Pages Navigation -->
      <div class="w-full md:w-1/2">
        <h3 class="text-white uppercase text-sm font-semibold mb-4">{{ __('Pages', TEXT_DOMAIN) }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <?php
          wp_nav_menu( array(
            'theme_location' => 'footer-menu-1',
            'container'      => false,
            'menu_class'     => 'space-y-4 menu-wrapper',
          ) );
          ?>

          <?php
          wp_nav_menu( array(
            'theme_location' => 'footer-menu-2',
            'container'      => false,
            'menu_class'     => 'space-y-4 menu-wrapper',
          ) );
          ?>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-gray-800 text-sm">
      @if($footer_copyrights)
        <p>{!! $footer_copyrights !!}</p>
      @endif
      <div class="flex gap-8 mt-4 md:mt-0">
        <?php
        wp_nav_menu( array(
          'theme_location' => 'footer_legal',
          'container'      => false,
          'menu_class'     => 'flex gap-8',
          'add_li_class'   => 'hover:text-primary-lime transition-colors'
        ) );
        ?>
      </div>
    </div>
  </div>
</footer>
