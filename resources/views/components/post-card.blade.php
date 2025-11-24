@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\TextColor;
@endphp

@props([
    'featured' => false,
    'post' => null,
])

@php
  // Get post data
  $postId = $post ?? get_the_ID();
  $title = get_the_title($postId);
  $excerpt = get_the_excerpt($postId);
  $permalink = get_permalink($postId);
  $thumbnail = get_the_post_thumbnail_url($postId, $featured ? 'large' : 'medium_large');
  $date = get_the_date('M j, Y', $postId);
  $author = get_the_author_meta('display_name', get_post_field('post_author', $postId));
  $authorAvatar = get_avatar_url(get_post_field('post_author', $postId), ['size' => 32]);

  // Get up to three tags (featured) or two (regular)
  $tags = get_the_tags($postId) ?: [];
  $tagLimit = $featured ? 3 : 2;
  $displayTags = array_slice($tags, 0, $tagLimit);

  // Reading time (rough estimate)
  $content = get_post_field('post_content', $postId);
  $wordCount = str_word_count(strip_tags($content));
  $readingTime = ceil($wordCount / 200); // Average reading speed of 200 words per minute
@endphp

<article class="{{ $featured ? 'featured-post-card' : 'post-card' }}">
  <a href="{{ $permalink }}" class="block">
    <div class="flex flex-col h-full p-6">
      {{-- Image --}}
      <div class="{{ $featured ? 'rounded-t-[12px]' : 'rounded-[12px]' }} w-full relative overflow-hidden mb-6">
        @if($thumbnail)
          <img
            src="{{ $thumbnail }}"
            alt="{{ $title }}"
            class="w-full h-full object-cover transition-transform duration-300 {{ $featured ? 'aspect-[16/9]' : 'aspect-[16/9]' }}"
            loading="lazy"
          >
        @else
          <div class="w-full {{ $featured ? 'aspect-[16/9]' : 'aspect-[16/9]' }} bg-gradient-to-br from-primary-green-neon to-primary-yellow"></div>
        @endif
      </div>

      {{-- Content --}}
      <div class="flex flex-col h-full">
        {{-- Tag Badges --}}
        @if($featured || !empty($displayTags))
          <div class="flex flex-wrap justify-between mb-6">
            <div class="flex flex-wrap gap-2">
              @if($featured)
                <x-badge variant="default" size="sm" rounded="sm" class="bg-secondary-purple">
                  <x-text
                    :as="TextTag::SPAN"
                    :size="TextSize::CAPTION"
                    class="text-primary-dark"
                  >
                    Featured
                  </x-text>
                </x-badge>
              @endif

              @foreach($displayTags as $tag)
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

            <div>
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::CAPTION"
                class="text-primary-dark"
              >
                {{ $readingTime }} min read
              </x-text>
            </div>
          </div>
        @endif

        {{-- Title --}}
        <x-heading
          :as="$featured ? HeadingTag::H2 : HeadingTag::H3"
          :size="$featured ? HeadingSize::H3 : HeadingSize::H5"
          class="mb-6 line-clamp-2 transition-colors duration-200"
        >
          {!! $title !!}
        </x-heading>

        {{-- Excerpt --}}
        <x-text
          :as="TextTag::P"
          :size="$featured ? TextSize::BASE : TextSize::SMALL"
          class="text-neutral-400 mb-6 {{ $featured ? 'line-clamp-3' : 'line-clamp-2' }}"
        >
          {{ $excerpt }}
        </x-text>

        <div class="mt-auto">
          <a href="{{ $permalink }}" class="block">
            <x-text 
              :size="TextSize::SMALL" 
              :color="TextColor::LIGHT"
              class="inline-flex items-center gap-1 !no-underline hover:underline transition-all duration-200 ease-in-out">
                <span>Read more</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                  <path d="M7.00016 0.663574L5.82516 1.83857L10.4752 6.49691H0.333496V8.16357H10.4752L5.82516 12.8219L7.00016 13.9969L13.6668 7.33024L7.00016 0.663574Z" fill="black"/>
                </svg>
            </x-text>
          </a>
        </div>
      </div>
    </div>
  </a>
</article>
