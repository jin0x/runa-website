@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ContainerSize;
@endphp

@extends('layouts.app')

@section('content')
  <x-container :size="ContainerSize::LARGE" class="py-16 md:py-24">
    {{-- Blog Title --}}
    <div class="mb-12">
      <x-heading 
        :as="HeadingTag::H1" 
        :size="HeadingSize::DISPLAY_LARGE"
        class="mb-8"
      >
        Blog
      </x-heading>

      {{-- Category Pills --}}
      @php
        $categories = get_categories([
          'orderby' => 'count',
          'order' => 'DESC',
          'number' => 8,
          'hide_empty' => true,
        ]);
      @endphp
      
      @if(!empty($categories))
        <div class="flex flex-wrap gap-3">
          <a href="{{ get_permalink(get_option('page_for_posts')) }}">
            <x-badge 
              variant="default" 
              size="lg" 
              rounded="full"
              class="hover:bg-primary-green-neon hover:text-primary-black transition-colors cursor-pointer"
            >
              All Posts
            </x-badge>
          </a>
          @foreach($categories as $category)
            <a href="{{ get_category_link($category) }}">
              <x-badge 
                variant="default" 
                size="lg" 
                rounded="full"
                class="hover:bg-primary-green-neon hover:text-primary-black transition-colors cursor-pointer"
              >
                {{ $category->name }}
              </x-badge>
            </a>
          @endforeach
        </div>
      @endif
    </div>

    @if (! have_posts())
      <x-alert type="warning" class="mb-8">
        {!! __('Sorry, no results were found.', 'sage') !!}
      </x-alert>
      {!! get_search_form(false) !!}
    @else
      @php
        $postCount = 0;
        $posts = [];
        while(have_posts()) {
          the_post();
          $posts[] = get_the_ID();
          $postCount++;
        }
        rewind_posts();
      @endphp

      {{-- Featured Post (First Post) --}}
      @if($postCount > 0)
        @php the_post() @endphp
        <div class="mb-16">
          <x-post-card :featured="true" :post="get_the_ID()" />
        </div>
      @endif

      {{-- Regular Posts Grid --}}
      @if($postCount > 1)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
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
                  <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-green-neon text-primary-black font-medium">
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