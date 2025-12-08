@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ContainerSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\SectionSize;
@endphp

@extends('layouts.app')

@section('content')
  @php
    $searchQuery  = trim((string) get_search_query());
    $searchActive = $searchQuery !== '';

    $featuredActive = (string) get_query_var('featured') === '1';
    $isPaged = is_paged();
    $showFeatured = !$isPaged && !$featuredActive && !$searchActive;

    $featuredQuery = new WP_Query([
      'post_type'      => 'press_release',
      'post_status'    => 'publish',
      'meta_key'       => 'runa_featured_press_release',
      'meta_value'     => '1',
      'orderby'        => 'date',
      'order'          => 'DESC',
      'posts_per_page' => $showFeatured ? -1 : 1,
    ]);

    $hasFeaturedPosts = $featuredQuery->have_posts();
    $featuredCount    = ($showFeatured && $hasFeaturedPosts) ? (int) $featuredQuery->post_count : 0;

    // Press releases archive URL
    $pressBaseUrl = get_post_type_archive_link('press_release');
    $featuredFilterUrl = add_query_arg('featured', '1', $pressBaseUrl);

    $showFeaturedFilter = ($hasFeaturedPosts || $featuredActive) && !$searchActive;
    $showFilters = $showFeaturedFilter;

    $flag = [
      'paged'              => $isPaged,
      'searchQuery'        => $searchQuery,
      'searchActive'       => $searchActive,
      'featuredActive'     => $featuredActive,
      'showFeatured'       => $showFeatured,
      'showFeaturedFilter' => $showFeaturedFilter,
      'showFilters'        => $showFilters,
      'hasFeaturedPosts'   => $hasFeaturedPosts,
    ];

    $form_classes = 'w-full px-6 py-6 pr-12 flex items-center justify-between gap-3 bg-gray-50 rounded-md appearance-none focus:outline-none';
    $svg_search = '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 absolute top-1/2 -translate-y-1/2 pointer-events-none right-6"><g clip-path="url(#clip0_3437_42119)"><path d="M15.5 14.5H14.71L14.43 14.23C15.41 13.09 16 11.61 16 10C16 6.41 13.09 3.5 9.5 3.5C5.91 3.5 3 6.41 3 10C3 13.59 5.91 16.5 9.5 16.5C11.11 16.5 12.59 15.91 13.73 14.93L14 15.21V16L19 20.99L20.49 19.5L15.5 14.5ZM9.5 14.5C7.01 14.5 5 12.49 5 10C5 7.51 7.01 5.5 9.5 5.5C11.99 5.5 14 7.51 14 10C14 12.49 11.99 14.5 9.5 14.5Z" fill="currentColor"/></g><defs><clipPath id="clip0_3437_42119"><rect width="24" height="24" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>';

    // Get press releases background image from options
    $pressBackgroundImage = get_field('press_releases_background_image', 'options');
    $pressBgImageUrl = '';
    if (!empty($pressBackgroundImage) && is_array($pressBackgroundImage)) {
        $pressBgImageUrl = $pressBackgroundImage['url'] ?? '';
    }

    // Get background overlay opacity
    $pressBackgroundOpacity = get_field('press_releases_background_opacity', 'options') ?: 60;
    
    // Get background image position
    $pressBackgroundPosition = get_field('press_releases_background_position', 'options') ?: 'center center';
  @endphp

  {{-- Press Releases Hero Section --}}
  <x-section :size="SectionSize::NONE" classes="relative w-full h-[300px] md:h-[400px] overflow-hidden">
    {{-- Background Image --}}
    <div class="absolute inset-0 w-full h-full z-0">
      @if (!empty($pressBgImageUrl))
        <img
          src="{{ $pressBgImageUrl }}"
          alt="Press Releases"
          class="absolute inset-0 object-cover w-full h-full"
          style="object-position: {{ $pressBackgroundPosition }};"
        >
        {{-- Dark overlay for better text readability --}}
        <div class="absolute inset-0 bg-black z-10" style="opacity: {{ $pressBackgroundOpacity / 100 }};"></div>
      @else
        {{-- Fallback background if no image is provided --}}
        <div class="absolute inset-0 bg-primary-dark"></div>
      @endif
    </div>

    {{-- Title Overlay --}}
    <div class="absolute bottom-0 left-0 right-0 z-20 pb-16 px-4 lg:px-8">
      <x-container :size="ContainerSize::LARGE">
        <x-heading
          :as="HeadingTag::H1"
          :size="HeadingSize::DISPLAY_LARGE"
          class="text-white"
        >
          Press Releases
        </x-heading>
      </x-container>
    </div>
  </x-section>

  {{-- Content Section --}}
  <x-container :size="ContainerSize::LARGE" classes="py-16">    
    @if (! have_posts())
      <x-alert type="warning" class="mb-8">
        {!! __('Sorry, no press releases were found.', 'sage') !!}
      </x-alert>
    @else
      {{-- Featured press releases --}}
      @if($flag['showFeatured'] && $flag['hasFeaturedPosts'])
        <div class="grid grid-cols-1 gap-6 mb-12 {{ $featuredCount === 1 ? '' : 'md:grid-cols-2' }}">
          @while($featuredQuery->have_posts())
            @php $featuredQuery->the_post() @endphp
            <x-post-card :featured="true" :post="get_the_ID()" :single-featured="$featuredCount === 1" />
          @endwhile
        </div>
        @php wp_reset_postdata() @endphp
      @endif

      {{-- Remaining press releases - card grid format to match Figma --}}
      @if(have_posts())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
          @while(have_posts())
            @php the_post() @endphp
            
            {{-- Press Release Card --}}
            <article class="press-release-card flex flex-col bg-white rounded-lg p-6 shadow-lg h-full">
              {{-- Date --}}
              <x-text
                :as="TextTag::SPAN"
                :size="TextSize::SMALL"
                :color="TextColor::GRAY"
                class="font-medium block mb-6"
              >
                {{ get_the_date('M j, Y g:i A') }}
              </x-text>
              
              {{-- Title --}}
              <x-heading
                :as="HeadingTag::H4"
                :size="HeadingSize::H4_BOLD"
                class="mb-3"
              >
                {!! get_the_title() !!}
              </x-heading>
              
              {{-- Excerpt/Subtitle --}}
              @if(get_the_excerpt())
                <x-text
                  :as="TextTag::P"
                  :size="TextSize::LARGE"
                  class="text-gray-700 mb-6 line-clamp-3"
                >
                  {{ get_the_excerpt() }}...
                </x-text>
              @endif
              
              {{-- Read More Link --}}
              <div class="mt-auto">
                <a href="{{ get_permalink() }}" class="inline-flex items-center gap-2 !no-underline hover:underline text-base font-medium transition-all duration-200">
                  Read now
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" class="pt-0.5">
                    <path d="M8.00018 0.757812L6.65685 2.10114L11.2235 6.66781H0.666016V8.33447H11.2235L6.65685 12.9011L8.00018 14.2445L14.6668 7.57781L8.00018 0.757812Z" fill="currentColor"/>
                  </svg>
                </a>
              </div>
            </article>
          @endwhile
        </div>
      @endif

      {{-- Pagination --}}
      @php
        $pagination = paginate_links([
          'type' => 'array',
          'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
          'next_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
        ]);
      @endphp

      @if($pagination)
        <nav class="flex justify-center" aria-label="Pagination">
          <ul class="flex items-center gap-2">
            @foreach($pagination as $page)
              @php
                $isActive = strpos($page, 'current') !== false;
                $isEllipsis = strpos($page, 'dots') !== false;
              @endphp

              <li>
                @if($isEllipsis)
                  <span class="px-3 py-2 text-neutral-400">...</span>
                @elseif($isActive)
                  <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-green-neon text-primary-dark font-medium">
                    {!! strip_tags($page) !!}
                  </span>
                @else
                  <span class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-neutral-100 transition-colors">
                    {!! $page !!}
                  </span>
                @endif
              </li>
            @endforeach
          </ul>
        </nav>
      @endif
    @endif
  </x-container>
@endsection