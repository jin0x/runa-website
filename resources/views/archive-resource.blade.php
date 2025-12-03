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
    $activeCategoryIds = [];
    $queriedObject = get_queried_object();

    if ($queriedObject instanceof \WP_Term && $queriedObject->taxonomy === 'resource_category') {
      $activeCategoryIds[] = (int) $queriedObject->term_id;
    }

    $categoryActive = !empty($activeCategoryIds);
    $isPaged = is_paged();
    $showFeatured = !$isPaged && !$featuredActive && !$categoryActive && !$searchActive;

    $featuredQuery = new WP_Query([
      'post_type'      => 'resource',
      'post_status'    => 'publish',
      'meta_key'       => 'runa_featured_resource',
      'meta_value'     => '1',
      'orderby'        => 'date',
      'order'          => 'DESC',
      'posts_per_page' => $showFeatured ? -1 : 1,
    ]);

    $hasFeaturedPosts = $featuredQuery->have_posts();
    $featuredCount = ($showFeatured && $hasFeaturedPosts) ? (int) $featuredQuery->post_count : 0;

    $categories = get_terms([
      'taxonomy'   => 'resource_category',
      'orderby'    => 'name',
      'order'      => 'ASC',
      'hide_empty' => true,
    ]);

    // Resources archive URL
    $resourcesBaseUrl = get_post_type_archive_link('resource');
    $featuredFilterUrl = add_query_arg('featured', '1', $resourcesBaseUrl);

    $showFeaturedFilter = ($hasFeaturedPosts || $featuredActive) && !$categoryActive && !$searchActive;
    $showCategoryFilters = !empty($categories) && !$featuredActive && !$searchActive;
    $showFilters = $showFeaturedFilter || $showCategoryFilters;

    $flag = [
      'paged'                => $isPaged,
      'searchQuery'          => $searchQuery,
      'searchActive'         => $searchActive,
      'activeCategoryIds'    => $activeCategoryIds,
      'featuredActive'       => $featuredActive,
      'categoryActive'       => $categoryActive,
      'showFeatured'         => $showFeatured,
      'showFeaturedFilter'   => $showFeaturedFilter,
      'showCategoryFilters'  => $showCategoryFilters,
      'showFilters'          => $showFilters,
      'hasFeaturedPosts'     => $hasFeaturedPosts,
    ];

    $form_classes = 'w-full px-6 py-6 pr-12 flex items-center justify-between gap-3 bg-gray-50 rounded-md appearance-none focus:outline-none';
    $svg_search = '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 absolute top-1/2 -translate-y-1/2 pointer-events-none right-6"><g clip-path="url(#clip0_3437_42119)"><path d="M15.5 14.5H14.71L14.43 14.23C15.41 13.09 16 11.61 16 10C16 6.41 13.09 3.5 9.5 3.5C5.91 3.5 3 6.41 3 10C3 13.59 5.91 16.5 9.5 16.5C11.11 16.5 12.59 15.91 13.73 14.93L14 15.21V16L19 20.99L20.49 19.5L15.5 14.5ZM9.5 14.5C7.01 14.5 5 12.49 5 10C5 7.51 7.01 5.5 9.5 5.5C11.99 5.5 14 7.51 14 10C14 12.49 11.99 14.5 9.5 14.5Z" fill="currentColor"/></g><defs><clipPath id="clip0_3437_42119"><rect width="24" height="24" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>';

    // Get resources background image from options
    $resourcesBackgroundImage = get_field('resources_background_image', 'options');
    $resourcesBgImageUrl = '';
    if (!empty($resourcesBackgroundImage) && is_array($resourcesBackgroundImage)) {
        $resourcesBgImageUrl = $resourcesBackgroundImage['url'] ?? '';
    }

    // Get background overlay opacity
    $resourcesBackgroundOpacity = get_field('resources_background_opacity', 'options') ?: 60;
    
    // Get background image position
    $resourcesBackgroundPosition = get_field('resources_background_position', 'options') ?: 'center center';
  @endphp

  {{-- Resources Hero Section --}}
  <x-section :size="SectionSize::NONE" classes="relative w-full h-[300px] md:h-[400px] overflow-hidden">
    {{-- Background Image --}}
    <div class="absolute inset-0 w-full h-full z-0">
      @if (!empty($resourcesBgImageUrl))
        <img
          src="{{ $resourcesBgImageUrl }}"
          alt="Resources"
          class="absolute inset-0 object-cover w-full h-full"
          style="object-position: {{ $resourcesBackgroundPosition }};"
        >
        {{-- Dark overlay for better text readability --}}
        <div class="absolute inset-0 bg-black z-10" style="opacity: {{ $resourcesBackgroundOpacity / 100 }};"></div>
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
          Resources
        </x-heading>
      </x-container>
    </div>
  </x-section>

  {{-- Filters and Content Section --}}
  <x-container :size="ContainerSize::LARGE" classes="py-16">
    {{-- Filters + Search --}}
    <div class="mb-12 flex flex-col gap-y-6">
      <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[6.7fr_3.3fr] gap-x-6">
        <div class="flex flex-col">
          <x-text
          :as="TextTag::SPAN"
          :size="TextSize::XSMALL"
          :color="TextColor::GRAY"
          class="font-medium mb-6"
          >
            Filter by
          </x-text>

          <div class="flex flex-wrap gap-3">
            @if($flag['showFilters'])
             @if($flag['showFeaturedFilter'])
               <a href="{{ $flag['featuredActive'] ? $resourcesBaseUrl : $featuredFilterUrl }}">
                 <x-badge variant="default" size="sm" rounded="sm" class="!bg-secondary-purple">
                   <x-text
                     :as="TextTag::SPAN"
                     :size="TextSize::CAPTION"
                     class="text-primary-dark !flex items-center gap-3 !normal-case"
                   >
                     Featured
 
                     @if($flag['featuredActive'])
                       <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <g clip-path="url(#clip0_4548_740)">
                           <path d="M7.99992 1.33331C4.31325 1.33331 1.33325 4.31331 1.33325 7.99998C1.33325 11.6866 4.31325 14.6666 7.99992 14.6666C11.6866 14.6666 14.6666 11.6866 14.6666 7.99998C14.6666 4.31331 11.6866 1.33331 7.99992 1.33331ZM11.3333 10.3933L10.3933 11.3333L7.99992 8.93998L5.60659 11.3333L4.66659 10.3933L7.05992 7.99998L4.66659 5.60665L5.60659 4.66665L7.99992 7.05998L10.3933 4.66665L11.3333 5.60665L8.93992 7.99998L11.3333 10.3933Z" fill="black"/>
                         </g>
                         <defs>
                           <clipPath id="clip0_4548_740">
                             <rect width="16" height="16" fill="white"/>
                           </clipPath>
                         </defs>
                       </svg>
                     @endif
                   </x-text>
                 </x-badge>
               </a>
             @endif

             @if($flag['showCategoryFilters'])
               @foreach($categories as $category)
                 @php
                   $isCategoryActive = in_array((int) $category->term_id, $flag['activeCategoryIds'], true);
                 @endphp
                 @continue($flag['categoryActive'] && !$isCategoryActive)
 
                 <a href="{{ $isCategoryActive ? $resourcesBaseUrl : get_term_link($category) }}">
                   <x-badge variant="default" size="sm" rounded="sm" class="bg-secondary-cyan">
                     <x-text
                       :as="TextTag::SPAN"
                       :size="TextSize::CAPTION"
                       class="text-primary-dark !flex items-center gap-3 !normal-case"
                     >
                       {{ ucwords(strtolower($category->name)) }}
 
                       @if($isCategoryActive)
                         <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <g clip-path="url(#clip0_4548_740)">
                             <path d="M7.99992 1.33331C4.31325 1.33331 1.33325 4.31331 1.33325 7.99998C1.33325 11.6866 4.31325 14.6666 7.99992 14.6666C11.6866 14.6666 14.6666 11.6866 14.6666 7.99998C14.6666 4.31331 11.6866 1.33331 7.99992 1.33331ZM11.3333 10.3933L10.3933 11.3333L7.99992 8.93998L5.60659 11.3333L4.66659 10.3933L7.05992 7.99998L4.66659 5.60665L5.60659 4.66665L7.99992 7.05998L10.3933 4.66665L11.3333 5.60665L8.93992 7.99998L11.3333 10.3933Z" fill="black"/>
                           </g>
                           <defs>
                             <clipPath id="clip0_4548_740">
                               <rect width="16" height="16" fill="white"/>
                             </clipPath>
                           </defs>
                         </svg>
                       @endif
                     </x-text>
                   </x-badge>
                 </a>
               @endforeach
             @endif
            @endif
          </div>
        </div>

        <div class="resource-search w-full md:w-auto md:max-w-md">
          <form action="{{ $resourcesBaseUrl }}" method="get" class="w-full">
            <div class="w-full flex items-center">
              <input
                type="search"
                name="s"
                value="{{ esc_attr($searchQuery) }}"
                placeholder="Search resources"
                class="{{$form_classes}}"
              >
              <button type="submit" class="text-primary-dark font-medium relative">
                {!! $svg_search !!}
              </button>
            </div>
          </form>
        </div>
        
      </div>
    </div>
    
    @if (! have_posts())
      <x-alert type="warning" class="mb-8">
        {!! __('Sorry, no resources were found.', 'sage') !!}
      </x-alert>
    @else
      {{-- Featured resources --}}
      @if($flag['showFeatured'] && $flag['hasFeaturedPosts'])
        <div class="grid grid-cols-1 gap-6 mb-12 {{ $featuredCount === 1 ? '' : 'md:grid-cols-2' }}">
          @while($featuredQuery->have_posts())
            @php $featuredQuery->the_post() @endphp
            <x-post-card :featured="true" :post="get_the_ID()" :single-featured="$featuredCount === 1" />
          @endwhile
        </div>
        @php wp_reset_postdata() @endphp
      @endif

      {{-- Remaining resources grid --}}
      @if(have_posts())
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
        @while(have_posts())
          @php the_post() @endphp
          <x-post-card :featured="false" :post="get_the_ID()" />
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