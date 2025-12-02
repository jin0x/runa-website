@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use function App\Helpers\get_related_posts_ids;

  $post_id = get_the_ID();
  $tags = get_the_tags($post_id) ?: [];
  $isFeatured = (string) get_post_meta($post_id, RUNA_FEATURED_POST_META_KEY, true) === '1';
  $content = get_post_field('post_content', $post_id);
  $wordCount = str_word_count(strip_tags($content));
  $readingTime = ceil($wordCount / 200);
  $authorName = get_the_author();
  $authorUrl = get_author_posts_url(get_the_author_meta('ID'));
  $publishDate = get_the_date('M j, Y');
  $related_posts_count = 2;
  $related_posts_ids = get_related_posts_ids($post_id, $related_posts_count);

  $postsPageId       = get_option('page_for_posts');
  $blogBaseUrl       = $postsPageId ? get_permalink($postsPageId) : App\Helpers\get_frontend_home_url();

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
@endphp
<article <?php post_class('h-entry w-full relative py-20 overflow-visible md:pt-40'); ?>>
  <div class="w-full mx-auto relative px-6 lg:px-8 lg:max-w-[1600px] ">
    <div class="w-full mx-auto relative lg:max-w-7xl">
      <x-text 
        href="{{ $blogBaseUrl }}"
        :as="TextTag::A" 
        :size="TextSize::SMALL" 
        :color="TextColor::LIGHT"
        class="inline-flex items-center gap-2 !no-underline hover:underline transition-all duration-200 ease-in-out">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class=" rotate-180">
            <path d="M7.00016 0.663574L5.82516 1.83857L10.4752 6.49691H0.333496V8.16357H10.4752L5.82516 12.8219L7.00016 13.9969L13.6668 7.33024L7.00016 0.663574Z" fill="black"/>
          </svg>
          <span>Back to Articles</span>
      </x-text>
      <div class="flex w-full items-center rounded-full mt-6 mb-12">
        <div class="flex-1 border-b border-secondary-purple"></div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-[7.5fr_2.5fr] gap-x-12">
        {{-- Content --}}
        <div class="space-y-10">
          <header class="mb-8">
            <x-heading
              :as="HeadingTag::H1"
              :size="HeadingSize::HERO"
              class="mb-3 break-smart"
            >
              {{ $title }}
            </x-heading>
          </header>

          <div class="flex w-full items-center rounded-full my-6">
            <div class="flex-1 border-b border-secondary-purple"></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 md:items-center md:justify-between gap-4 md:gap-6">
            <div class="flex flex-wrap items-center">
              @if($isFeatured)
                <x-badge variant="default" size="sm" rounded="sm" class="bg-secondary-purple mr-2">
                  <x-text
                    :as="TextTag::SPAN"
                    :size="TextSize::CAPTION"
                    class="text-primary-dark !normal-case"
                  >
                    Featured
                  </x-text>
                </x-badge>
              @endif

              @if(!empty($tags))
                <div class="flex flex-wrap items-center gap-2">
                  @foreach($tags as $tag)
                    <x-badge variant="default" size="sm" rounded="sm" class="bg-secondary-cyan">
                      <x-text
                        :as="TextTag::SPAN"
                        :size="TextSize::CAPTION"
                        class="text-primary-dark"
                      >
                        {{ $tag->name }}
                      </x-text>
                    </x-badge>
                  @endforeach
                </div>
              @endif

              @if($isFeatured || !empty($tags))
                <span class="mx-6 h-6 w-px bg-neutral-300" aria-hidden="true"></span>
              @endif

              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::CAPTION"
                class="text-primary-dark"
              >
                {{ $readingTime }} min read
              </x-text>
            </div>

            <div class="flex items-center gap-6">
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::CAPTION"
                class="text-primary-dark flex items-center gap-1"
              >
                <span class="text-neutral-500">by</span>
                <a href="{{ $authorUrl }}" class="underline underline-offset-2 decoration-2">
                  {{ $authorName }}
                </a>
              </x-text>

              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::CAPTION"
                class="text-primary-dark"
              >
                {{ $publishDate }}
              </x-text>
            </div>
          </div>

          @if (has_post_thumbnail())
            <div class="mb-8 rounded-lg overflow-hidden">
              {!! get_the_post_thumbnail($post_id, 'full', ['class' => 'w-full h-auto']) !!}
            </div>
          @endif

          <div class="e-content prose max-w-none">
            @php the_content(); @endphp
          </div>

        </div>

        {{-- Sidebar --}}
        <div class="sidebar">
          {{-- Related posts --}}
          @if(!empty($related_posts_ids))
            <div class="space-y-12">
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::LARGE"
                class="block uppercase tracking-[0.08em] font-bold text-primary-dark mb-8"
              >
                Related articles
              </x-text>

              <div class="space-y-6">
                @foreach($related_posts_ids as $related_post_id)
                  @php
                    $related_post = get_post($related_post_id);
                    if (!$related_post) {
                      continue;
                    }
                    $title = html_entity_decode(get_the_title($related_post));
                    $excerpt = get_the_excerpt($related_post) ?: '';
                  @endphp

                  <article class="space-y-4">
                    <x-heading
                      :as="HeadingTag::H3"
                      :size="HeadingSize::H3"
                      class="leading-tight"
                    >
                      {{ $title }}
                    </x-heading>

                    @if(!empty($excerpt))
                      <x-text
                        :as="TextTag::P"
                        :size="TextSize::BASE"
                        class="text-neutral-500 max-w-xl"
                      >
                        {{ $excerpt }}
                      </x-text>
                    @endif
                      <x-text 
                        href="{{ get_permalink($related_post) }}"
                        :as="TextTag::A" 
                        :size="TextSize::SMALL" 
                        :color="TextColor::LIGHT"
                        class="inline-flex items-center gap-2 !no-underline hover:underline transition-all duration-200 ease-in-out">
                          <span>Read more</span>
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M7.00016 0.663574L5.82516 1.83857L10.4752 6.49691H0.333496V8.16357H10.4752L5.82516 12.8219L7.00016 13.9969L13.6668 7.33024L7.00016 0.663574Z" fill="black"/>
                          </svg>
                      </x-text>
                  </article>
                @endforeach
              </div>
            </div>
          @endif

          {{-- Divider --}}
          <div class="flex w-full items-center rounded-full my-12">
            <div class="flex-1 border-b border-secondary-purple"></div>
          </div>

          {{-- Newsletter --}}
          <div>
             <x-text
              :as="TextTag::SPAN"
              :size="TextSize::MEDIUM"
              class="block tracking-[0.08em] font-bold text-primary-dark mb-6"
            >
              Sign up for our newsletter
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
                class="flex-1 px-4 py-3 bg-[#262626] placeholder-white text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-lime focus:border-transparent max-h-10"
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

          {{-- Divider --}}
          <div class="flex w-full items-center rounded-full my-12">
            <div class="flex-1 border-b border-secondary-purple"></div>
          </div>

          {{-- Social networks --}}
          <div>
            <x-text
              :as="TextTag::SPAN"
              :size="TextSize::MEDIUM"
              class="block tracking-[0.08em] font-bold text-primary-dark mb-6"
            >
              Join us
            </x-text>
            <div class="flex gap-3">
              @if ( $github_url )
                <x-social-icon
                  network="github"
                  href="{{ esc_url($github_url) }}"
                  aria="Visit GitHub"
                />
              @endif

              @if ( $twitter_url )
                <x-social-icon
                  network="twitter"
                  href="{{ esc_url($twitter_url) }}"
                  aria="Visit Twitter"
                />
              @endif

              @if ( $linkedin_url )
                <x-social-icon
                  network="linkedin"
                  href="{{ esc_url($linkedin_url) }}"
                  aria="Visit LinkedIn"
                />
              @endif

              @if ( $facebook_url )
                <x-social-icon
                  network="facebook"
                  href="{{ esc_url($facebook_url) }}"
                  aria="Visit Facebook"
                />
              @endif

              @if ( $instagram_url )
                <x-social-icon
                  network="instagram"
                  href="{{ esc_url($instagram_url) }}"
                  aria="Visit Instagram"
                />
              @endif

              @if ( $x_url )
                <x-social-icon
                  network="x"
                  href="{{ esc_url($x_url) }}"
                  aria="Visit X"
                />
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</article>
