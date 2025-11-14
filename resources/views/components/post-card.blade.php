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
  // Get post data
  $postId = $post ?? get_the_ID();
  $title = get_the_title($postId);
  $excerpt = get_the_excerpt($postId);
  $permalink = get_permalink($postId);
  $thumbnail = get_the_post_thumbnail_url($postId, $featured ? 'large' : 'medium_large');
  $date = get_the_date('M j, Y', $postId);
  $author = get_the_author_meta('display_name', get_post_field('post_author', $postId));
  $authorAvatar = get_avatar_url(get_post_field('post_author', $postId), ['size' => 32]);

  // Get primary tag
  $tags = get_the_tags($postId);
  $primaryTag = !empty($tags) ? $tags[0] : null;

  // Reading time (rough estimate)
  $content = get_post_field('post_content', $postId);
  $wordCount = str_word_count(strip_tags($content));
  $readingTime = ceil($wordCount / 200); // Average reading speed of 200 words per minute
@endphp

<article class="{{ $featured ? 'featured-post-card' : 'post-card' }} group">
  <a href="{{ $permalink }}" class="block">
    <div class="{{ $featured ? 'flex flex-col lg:flex-row md:gap-8' : 'flex flex-col h-full' }}">
      {{-- Image --}}
      <div class="{{ $featured ? 'lg:w-1/2' : 'rounded-t-2xl w-full' }} relative overflow-hidden bg-neutral-100">
        @if($thumbnail)
          <img
            src="{{ $thumbnail }}"
            alt="{{ $title }}"
            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 {{ $featured ? 'aspect-[16/8]' : 'aspect-[4/2]' }}"
            loading="lazy"
          >
        @else
          <div class="w-full {{ $featured ? 'aspect-[16/10]' : 'aspect-[4/3]' }} bg-gradient-to-br from-primary-green-neon to-primary-yellow"></div>
        @endif
      </div>

      {{-- Content --}}
      <div class="{{ $featured ? 'lg:w-1/2 flex flex-col justify-center py-6 pl-6 pr-6 lg:pl-0' : 'flex-1 p-6' }}">
        {{-- Tag Badge --}}
        @if($primaryTag)
          <div class="mb-4">
            <x-badge variant="default" size="sm" rounded="full"
              class="bg-primary-green-neon text-primary-dark">
              {{ $primaryTag->name }}
            </x-badge>
          </div>
        @endif

        {{-- Title --}}
        <x-heading
          :as="$featured ? HeadingTag::H2 : HeadingTag::H3"
          :size="$featured ? HeadingSize::H3 : HeadingSize::H5"
          class="mb-3 line-clamp-2 group-hover:text-primary-green-neon transition-colors duration-200"
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

        {{-- Meta --}}
        <div class="flex items-center gap-2 text-sm text-neutral-400 mt-auto">
          <time datetime="{{ get_the_date('c', $postId) }}">{{ $date }}</time>
          <span>•</span>
          <span>{{ $readingTime }} min read</span>
        </div>
      </div>
    </div>
  </a>
</article>
