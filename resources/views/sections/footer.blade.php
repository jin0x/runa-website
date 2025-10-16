<?php
use App\Enums\TextTag;
use App\Enums\TextSize;
use App\Enums\TextColor;

$footer_logo = get_field('footer_logo', 'option');
$footer_copyrights = get_field('copyrights', 'option');

// Get social networks from the repeater
$social_networks_array = get_field('social_networks', 'option') ?: [];

// Initialize variables for social networks
$github_url = '';
$twitter_url = '';
$linkedin_url = '';
$facebook_url = '';
$instagram_url = '';
$x_url = '';

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
  } elseif ($network_type === 'facebook') {
    $facebook_url = $network_url;
  } elseif ($network_type === 'instagram') {
    $instagram_url = $network_url;
  } elseif ($network_type === 'x') {
    $x_url = $network_url;
  }
}
?>

<footer class="footer bg-primary-dark text-gray-300 py-16 relative" aria-labelledby="footer-heading">
  <h2 id="footer-heading" class="sr-only">Footer</h2>
  <div class="container mx-auto px-4">
    <!-- Top Section: Logo Only -->
    <div class="mb-12 lg:mb-16">
      @if($footer_logo)
        <a href="<?php echo home_url('/'); ?>">
          <img src="{{ $footer_logo['url'] }}" alt="{{ $footer_logo['alt'] }}" class="h-10 w-auto">
        </a>
      @endif
    </div>

    <!-- Middle Section: Social Icons + Widget Areas -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
      <!-- Social Icons Column -->
      <div class="lg:col-span-3 max-w-xs">
        <!-- Newsletter Signup Section -->
        <div class="mb-6">
          <x-text
            :as="TextTag::P"
            :size="TextSize::CAPTION"
            :color="TextColor::GREEN_SOFT"
            class="mb-6"
          >
            SIGN UP FOR OUR NEWSLETTER
          </x-text>
          
          <form action="#" method="POST" class="flex gap-2 items-center mb-8 max-w-3xs">
            <?php wp_nonce_field('newsletter_signup', 'newsletter_nonce'); ?>
            <label for="newsletter-email" class="sr-only">Email address</label>
            <input 
              type="email" 
              id="newsletter-email" 
              name="email" 
              placeholder="Email address"
              required
              class="flex-1 px-4 py-3 bg-neutral-dark-60 placeholder-gray-500 rounded-full focus:outline-none focus:ring-2 focus:ring-primary-lime focus:border-transparent max-h-10"
            >
            <button 
              type="submit"
              class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-[#00FFA3] via-[#93FF82] to-[#EEFC51] rounded-full flex items-center justify-center hover:opacity-90 transition-opacity"
              aria-label="Subscribe to newsletter"
            >
              <svg width="40" height="40" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_3808_23616)">
                  <path d="M22.7891 16L16.8945 21.8945L15.834 20.834L19.917 16.75H9V15.25H19.917L15.834 11.166L16.8945 10.1055L22.7891 16Z" fill="black"/>
                </g>
                <defs>
                  <clipPath id="clip0_3808_23616">
                    <rect width="20" height="20" fill="white" transform="translate(6 6)"/>
                  </clipPath>
                </defs>
              </svg>
            </button>
          </form>
        </div>

        <div>
          <x-text
            :as="TextTag::P"
            :size="TextSize::CAPTION"
            :color="TextColor::GREEN_SOFT"
            class="mb-4"
          >
            JOIN US
          </x-text>

        <div class="flex gap-3">
          @if ( $github_url )
            <a href="<?php echo esc_url($github_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-6 w-6" width="24" height="25" viewBox="0 0 24 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2.27344C6.475 2.27344 2 6.74844 2 12.2734C1.99887 14.3727 2.65882 16.419 3.88622 18.1221C5.11362 19.8252 6.84615 21.0985 8.838 21.7614C9.338 21.8484 9.525 21.5484 9.525 21.2854C9.525 21.0484 9.512 20.2614 9.512 19.4234C7 19.8864 6.35 18.8114 6.15 18.2484C6.037 17.9604 5.55 17.0734 5.125 16.8354C4.775 16.6484 4.275 16.1854 5.112 16.1734C5.9 16.1604 6.462 16.8984 6.65 17.1984C7.55 18.7104 8.988 18.2854 9.562 18.0234C9.65 17.3734 9.912 16.9364 10.2 16.6864C7.975 16.4364 5.65 15.5734 5.65 11.7484C5.65 10.6604 6.037 9.76144 6.675 9.06044C6.575 8.81044 6.225 7.78544 6.775 6.41044C6.775 6.41044 7.612 6.14844 9.525 7.43644C10.3391 7.2105 11.1802 7.09678 12.025 7.09844C12.875 7.09844 13.725 7.21044 14.525 7.43544C16.437 6.13544 17.275 6.41144 17.275 6.41144C17.825 7.78644 17.475 8.81144 17.375 9.06144C18.012 9.76144 18.4 10.6484 18.4 11.7484C18.4 15.5864 16.063 16.4364 13.838 16.6864C14.2 16.9984 14.513 17.5984 14.513 18.5364C14.513 19.8734 14.5 20.9484 14.5 21.2864C14.5 21.5484 14.688 21.8604 15.188 21.7604C17.173 21.0902 18.8979 19.8144 20.1199 18.1126C21.3419 16.4107 21.9994 14.3686 22 12.2734C22 6.74844 17.525 2.27344 12 2.27344Z" fill="#F5F5F5"/>
              </svg>
            </a>
          @endif

          @if ( $twitter_url )
            <a href="<?php echo esc_url($twitter_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-5 w-5" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M13.0087 24H12.956C6.35573 24 0.985886 18.6286 0.985886 12.0264V11.9736C0.985886 5.37142 6.35573 0 12.956 0H13.0087C19.609 0 24.9788 5.37142 24.9788 11.9736V12.0264C24.9788 18.6286 19.609 24 13.0087 24ZM12.956 0.812375C6.80322 0.812375 1.79802 5.81904 1.79802 11.9736V12.0264C1.79802 18.181 6.80322 23.1876 12.956 23.1876H13.0087C19.1615 23.1876 24.1667 18.181 24.1667 12.0264V11.9736C24.1667 5.81904 19.1615 0.812375 13.0087 0.812375H12.956Z" fill="currentColor"/>
                <path d="M6.0933 5.65527L11.4388 12.8042L6.06 18.6167H7.2709L11.9805 13.528L15.7853 18.6167H19.9053L14.2593 11.0657L19.2661 5.65527H18.0552L13.7184 10.3419L10.2141 5.65527H6.0941H6.0933ZM7.87349 6.54728H9.76578L18.1235 17.7247H16.2312L7.87349 6.54728Z" fill="currentColor"/>
              </svg>
            </a>
          @endif

          @if ( $linkedin_url )
            <a href="<?php echo esc_url($linkedin_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-5 w-5" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M13.0017 24H12.9489C6.34867 24 0.978821 18.6286 0.978821 12.0264V11.9736C0.978821 5.37143 6.34867 0 12.9489 0H13.0017C19.6019 0 24.9718 5.37143 24.9718 11.9736V12.0264C24.9718 18.6286 19.6019 24 13.0017 24ZM12.9489 0.812375C6.79616 0.812375 1.79096 5.81905 1.79096 11.9736V12.0264C1.79096 18.181 6.79616 23.1876 12.9489 23.1876H13.0017C19.1544 23.1876 24.1596 18.181 24.1596 12.0264V11.9736C24.1596 5.81905 19.1544 0.812375 13.0017 0.812375H12.9489Z" fill="currentColor"/>
                <path d="M6.91323 8.11337C6.60787 7.82985 6.45601 7.47891 6.45601 7.06135C6.45601 6.64379 6.60868 6.2774 6.91323 5.99307C7.21859 5.70955 7.61167 5.56738 8.09327 5.56738C8.57486 5.56738 8.95251 5.70955 9.25706 5.99307C9.56243 6.27659 9.71429 6.63323 9.71429 7.06135C9.71429 7.48947 9.56161 7.82985 9.25706 8.11337C8.9517 8.39689 8.56431 8.53906 8.09327 8.53906C7.62223 8.53906 7.21859 8.39689 6.91323 8.11337ZM9.45766 9.73975V18.4322H6.71182V9.73975H9.45766Z" fill="currentColor"/>
                <path d="M18.5981 10.5989C19.1966 11.2488 19.4955 12.1408 19.4955 13.2765V18.2791H16.8878V13.629C16.8878 13.0563 16.7391 12.6111 16.4427 12.2943C16.1463 11.9775 15.7467 11.8182 15.2464 11.8182C14.7462 11.8182 14.3466 11.9767 14.0501 12.2943C13.7537 12.6111 13.6051 13.0563 13.6051 13.629V18.2791H10.9819V9.71583H13.6051V10.8515C13.8707 10.473 14.2288 10.174 14.6787 9.95385C15.1287 9.7337 15.6346 9.62402 16.1974 9.62402C17.1996 9.62402 18.0004 9.94897 18.5981 10.5989Z" fill="currentColor"/>
              </svg>
            </a>
          @endif

          @if ( $facebook_url )
            <a href="<?php echo esc_url($facebook_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12.0229 24H11.9701C5.36984 24 0 18.6286 0 12.0264V11.9736C0 5.37144 5.36984 0 11.9701 0H12.0229C18.6231 0 23.9929 5.37144 23.9929 11.9736V12.0264C23.9929 18.6286 18.6231 24 12.0229 24ZM11.9701 0.812375C5.81733 0.812375 0.812136 5.81906 0.812136 11.9736V12.0264C0.812136 18.181 5.81733 23.1876 11.9701 23.1876H12.0229C18.1756 23.1876 23.1808 18.181 23.1808 12.0264V11.9736C23.1808 5.81906 18.1756 0.812375 12.0229 0.812375H11.9701Z" fill="currentColor"/>
                <path d="M13.6104 9.30915V11.8348H16.7339L16.2393 15.2371H13.6104V23.0757C13.0834 23.1488 12.5441 23.187 11.9967 23.187C11.3649 23.187 10.7444 23.1366 10.1402 23.0391V15.2371H7.25953V11.8348H10.1402V8.74455C10.1402 6.82735 11.6938 5.27246 13.6112 5.27246V5.27409C13.6169 5.27409 13.6218 5.27246 13.6275 5.27246H16.7347V8.21488H14.7044C14.101 8.21488 13.6112 8.70474 13.6112 9.30834L13.6104 9.30915Z" fill="currentColor"/>
              </svg>
            </a>
          @endif

          @if ( $instagram_url )
            <a href="<?php echo esc_url($instagram_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12.0158 24H11.963C5.36279 24 -0.00705719 18.6286 -0.00705719 12.0264V11.9736C-0.00705719 5.37142 5.36279 0 11.963 0H12.0158C18.616 0 23.9859 5.37142 23.9859 11.9736V12.0264C23.9859 18.6286 18.616 24 12.0158 24ZM11.963 0.812375C5.81028 0.812375 0.805079 5.81904 0.805079 11.9736V12.0264C0.805079 18.181 5.81028 23.1876 11.963 23.1876H12.0158C18.1686 23.1876 23.1737 18.181 23.1737 12.0264V11.9736C23.1737 5.81904 18.1686 0.812375 12.0158 0.812375H11.963Z" fill="currentColor"/>
                <path d="M15.576 5.12402H8.40318C6.42157 5.12402 4.80948 6.73659 4.80948 8.71878V15.2828C4.80948 17.265 6.42157 18.8775 8.40318 18.8775H15.576C17.5576 18.8775 19.1697 17.265 19.1697 15.2828V8.71878C19.1697 6.73659 17.5576 5.12402 15.576 5.12402ZM6.07722 8.71878C6.07722 7.43604 7.12082 6.39213 8.40318 6.39213H15.576C16.8583 6.39213 17.9019 7.43604 17.9019 8.71878V15.2828C17.9019 16.5655 16.8583 17.6094 15.576 17.6094H8.40318C7.12082 17.6094 6.07722 16.5655 6.07722 15.2828V8.71878Z" fill="currentColor"/>
                <path d="M11.9895 15.3437C13.8323 15.3437 15.3323 13.8441 15.3323 12C15.3323 10.1559 13.8331 8.65625 11.9895 8.65625C10.146 8.65625 8.64677 10.1559 8.64677 12C8.64677 13.8441 10.146 15.3437 11.9895 15.3437ZM11.9895 9.92517C13.1338 9.92517 14.0645 10.8562 14.0645 12.0008C14.0645 13.1454 13.1338 14.0764 11.9895 14.0764C10.8452 14.0764 9.91451 13.1454 9.91451 12.0008C9.91451 10.8562 10.8452 9.92517 11.9895 9.92517Z" fill="currentColor"/>
                <path d="M15.6418 9.19637C16.138 9.19637 16.5424 8.79264 16.5424 8.29546C16.5424 7.79829 16.1388 7.39453 15.6418 7.39453C15.1448 7.39453 14.7411 7.79829 14.7411 8.29546C14.7411 8.79264 15.1448 9.19637 15.6418 9.19637Z" fill="currentColor"/>
              </svg>
            </a>
          @endif

          @if ( $x_url )
            <a href="<?php echo esc_url($x_url); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
              <svg class="h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
            </a>
          @endif
        </div>
      </div>
      </div>

      <!-- Widget Areas -->
      <div class="lg:col-span-9">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Footer Widget Area 1 -->
          <div class="space-y-4">
            <?php if ( is_active_sidebar( 'footer-widget-1' ) ) : ?>
              <?php dynamic_sidebar( 'footer-widget-1' ); ?>
            <?php endif; ?>
          </div>

          <!-- Footer Widget Area 2 -->
          <div class="space-y-4">
            <?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
              <?php dynamic_sidebar( 'footer-widget-2' ); ?>
            <?php endif; ?>
          </div>

          <!-- Footer Widget Area 3 -->
          <div class="space-y-4">
            <?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
              <?php dynamic_sidebar( 'footer-widget-3' ); ?>
            <?php endif; ?>
          </div>

          <!-- Footer Widget Area 4 -->
          <div class="space-y-4">
            <?php if ( is_active_sidebar( 'footer-widget-4' ) ) : ?>
              <?php dynamic_sidebar( 'footer-widget-4' ); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="flex flex-col md:flex-row items-center gap-4 lg:gap-6 text-3xs">
      @if($footer_copyrights)
        <span>{!! $footer_copyrights !!}</span>
      @endif
      <div class="flex gap-8 mt-4 md:mt-0">
        <?php
        wp_nav_menu( array(
          'theme_location' => 'footer_menu',
          'container'      => false,
          'menu_class'     => 'flex gap-8 flex-wrap',
          'add_li_class'   => 'hover:text-primary-lime transition-colors whitespace-nowrap'
        ) );
        ?>
      </div>
    </div>
  </div>
</footer>
