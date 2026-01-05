@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\ContainerSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\SectionSize;

  // Get search query
  $searchQuery  = trim((string) get_search_query());
  $searchActive = $searchQuery !== '';

  // Get all format terms (for Formats tab)
  $formats = get_terms([
    'taxonomy'   => 'resource_format',
    'orderby'    => 'name',
    'order'      => 'ASC',
    'hide_empty' => true, // Only show formats with posts
  ]);

  // Get all topic terms (for Topics tab)
  $topics = get_terms([
    'taxonomy'   => 'resource_topic',
    'orderby'    => 'name',
    'order'      => 'ASC',
    'hide_empty' => true, // Only show topics with posts
  ]);

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

  // Resources archive URL
  $resourcesBaseUrl = get_post_type_archive_link('resource');

  // SVG icons
  $svg_search = '<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 absolute top-1/2 -translate-y-1/2 pointer-events-none right-6"><g clip-path="url(#clip0_3437_42119)"><path d="M15.5 14.5H14.71L14.43 14.23C15.41 13.09 16 11.61 16 10C16 6.41 13.09 3.5 9.5 3.5C5.91 3.5 3 6.41 3 10C3 13.59 5.91 16.5 9.5 16.5C11.11 16.5 12.59 15.91 13.73 14.93L14 15.21V16L19 20.99L20.49 19.5L15.5 14.5ZM9.5 14.5C7.01 14.5 5 12.49 5 10C5 7.51 7.01 5.5 9.5 5.5C11.99 5.5 14 7.51 14 10C14 12.49 11.99 14.5 9.5 14.5Z" fill="currentColor"/></g><defs><clipPath id="clip0_3437_42119"><rect width="24" height="24" fill="white" transform="translate(0 0.5)"/></clipPath></defs></svg>';
  $svg_checkmark = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" fill="currentColor"/></svg>';
@endphp

@extends('layouts.app')

@section('content')
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
        <div class="absolute inset-0 bg-black z-10" style="opacity: {{ $resourcesBackgroundOpacity / 100 }};"></div>
      @else
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
    <div
      x-data="{
        activeTab: 'topics',
        activeFilters: [],
        featuredActive: false,
        searchQuery: '{{ esc_js($searchQuery) }}',
        loading: false,
        posts: [],
        featuredPosts: [],
        hasMore: true,
        page: 1,

        toggleTab(tab) {
          if (this.activeTab !== tab) {
            this.activeTab = tab;
            this.activeFilters = [];
            this.featuredActive = false;
            this.page = 1;
            this.loadPosts();
          }
        },

        toggleFilter(filter) {
          // Single-select behavior: if clicking the same filter, deactivate it; otherwise replace
          if (this.activeFilters.length === 1 && this.activeFilters[0] === filter) {
            this.activeFilters = [];
          } else {
            this.activeFilters = [filter];
            this.featuredActive = false; // Deactivate featured when selecting a format/topic
          }
          this.page = 1;
          this.loadPosts();
        },

        toggleFeatured() {
          this.featuredActive = !this.featuredActive;
          if (this.featuredActive) {
            this.activeFilters = []; // Clear format/topic filters when activating featured
          }
          this.page = 1;
          this.loadPosts();
        },

        isFilterActive(filter) {
          return this.activeFilters.includes(filter);
        },

        async loadPosts() {
          this.loading = true;

          const params = new URLSearchParams({
            action: 'filter_resources',
            tab: this.activeTab,
            filters: JSON.stringify(this.activeFilters),
            featured: this.featuredActive ? '1' : '0',
            search: this.searchQuery,
            page: this.page,
            nonce: '{{ wp_create_nonce("filter_resources") }}'
          });

          try {
            // Use current domain instead of configured WordPress URL to avoid CORS issues
            const ajaxUrl = window.location.origin + '/wp-admin/admin-ajax.php';
            const response = await fetch(ajaxUrl + '?' + params);
            const data = await response.json();

            if (data.success) {
              if (this.page === 1) {
                // When Featured filter is active, show posts in featured grid (larger cards)
                if (this.featuredActive) {
                  this.featuredPosts = data.data.posts;
                  this.posts = [];
                } else {
                  this.posts = data.data.posts;
                  this.featuredPosts = data.data.featured || [];
                }
              } else {
                // For pagination, append to appropriate array
                if (this.featuredActive) {
                  this.featuredPosts = [...this.featuredPosts, ...data.data.posts];
                } else {
                  this.posts = [...this.posts, ...data.data.posts];
                }
              }
              this.hasMore = data.data.has_more;
            }
          } catch (error) {
            console.error('Error loading posts:', error);
          } finally {
            this.loading = false;
          }
        },

        init() {
          this.loadPosts();
        }
      }"
      x-init="init()"
    >
      {{-- Tabs and Search --}}
      <div class="mb-12">
        <x-text
          :as="TextTag::P"
          :size="TextSize::BASE"
          :color="TextColor::GRAY"
          class="mb-4"
        >
          Explore Our Resource Library
        </x-text>

        <div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-6 items-start">
          {{-- Tab Buttons --}}
          <div class="flex gap-4">
            <button
              @click="toggleTab('topics')"
              :class="activeTab === 'topics' ? 'bg-primary-green-neon text-primary-dark' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
              class="px-8 py-4 rounded-lg font-medium transition-colors flex items-center gap-2 min-w-[180px] justify-between cursor-pointer"
            >
              <span>Topics</span>
              <span x-show="activeTab === 'topics'">{!! $svg_checkmark !!}</span>
            </button>

            <button
              @click="toggleTab('formats')"
              :class="activeTab === 'formats' ? 'bg-primary-green-neon text-primary-dark' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
              class="px-8 py-4 rounded-lg font-medium transition-colors flex items-center gap-2 min-w-[180px] justify-between cursor-pointer"
            >
              <span>Formats</span>
              <span x-show="activeTab === 'formats'">{!! $svg_checkmark !!}</span>
            </button>
          </div>

          {{-- Search --}}
          <div class="resource-search w-full md:w-auto md:max-w-md">
            <form action="{{ $resourcesBaseUrl }}" method="get" class="w-full">
              <div class="w-full flex items-center">
                <input
                  type="search"
                  name="s"
                  x-model="searchQuery"
                  @keyup.debounce.500ms="loadPosts()"
                  placeholder="Search resources"
                  class="w-full px-6 py-4 pr-12 flex items-center justify-between gap-3 bg-neutral-50 rounded-md appearance-none focus:outline-none"
                >
                <button type="button" class="text-primary-dark font-medium relative">
                  {!! $svg_search !!}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      {{-- Filters --}}
      <div class="mb-12">
        <x-text
          :as="TextTag::SPAN"
          :size="TextSize::XSMALL"
          :color="TextColor::GRAY"
          class="font-medium mb-6 block"
        >
          Filter by
        </x-text>

        <div class="flex flex-wrap gap-3">
          {{-- Featured Filter - Only show if there are featured posts or featured is active --}}
          <div x-show="activeFilters.length === 0 && (featuredPosts.length > 0 || featuredActive)">
            <button @click="toggleFeatured()" class="cursor-pointer">
              <x-badge variant="default" size="sm" rounded="sm" class="bg-secondary-purple min-h-[24px]">
                <x-text
                  :as="TextTag::SPAN"
                  :size="TextSize::CAPTION"
                  class="text-primary-dark !flex items-center gap-3 !normal-case"
                >
                  Featured
                  <span x-show="featuredActive">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g clip-path="url(#clip0_featured)">
                        <path d="M7.99992 1.33331C4.31325 1.33331 1.33325 4.31331 1.33325 7.99998C1.33325 11.6866 4.31325 14.6666 7.99992 14.6666C11.6866 14.6666 14.6666 11.6866 14.6666 7.99998C14.6666 4.31331 11.6866 1.33331 7.99992 1.33331ZM11.3333 10.3933L10.3933 11.3333L7.99992 8.93998L5.60659 11.3333L4.66659 10.3933L7.05992 7.99998L4.66659 5.60665L5.60659 4.66665L7.99992 7.05998L10.3933 4.66665L11.3333 5.60665L8.93992 7.99998L11.3333 10.3933Z" fill="black"/>
                      </g>
                      <defs>
                        <clipPath id="clip0_featured">
                          <rect width="16" height="16" fill="white"/>
                        </clipPath>
                      </defs>
                    </svg>
                  </span>
                </x-text>
              </x-badge>
            </button>
          </div>

          {{-- Topic Filters (shown when Topics tab is active) --}}
          <template x-if="activeTab === 'topics'">
            <div class="flex flex-wrap gap-3">
              @foreach($topics as $topic)
                <div x-show="activeFilters.length === 0 || isFilterActive('{{ $topic->term_id }}')">
                  <button @click="toggleFilter('{{ $topic->term_id }}')" class="cursor-pointer">
                    <x-badge variant="default" size="sm" rounded="sm" class="bg-secondary-cyan min-h-[24px]">
                      <x-text
                        :as="TextTag::SPAN"
                        :size="TextSize::CAPTION"
                        class="text-primary-dark !flex items-center gap-3 !normal-case"
                      >
                        {{ $topic->name }}
                        <span x-show="isFilterActive('{{ $topic->term_id }}')">
                          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_topic_{{ $topic->term_id }}">
                              <path d="M7.99992 1.33331C4.31325 1.33331 1.33325 4.31331 1.33325 7.99998C1.33325 11.6866 4.31325 14.6666 7.99992 14.6666C11.6866 14.6666 14.6666 11.6866 14.6666 7.99998C14.6666 4.31331 11.6866 1.33331 7.99992 1.33331ZM11.3333 10.3933L10.3933 11.3333L7.99992 8.93998L5.60659 11.3333L4.66659 10.3933L7.05992 7.99998L4.66659 5.60665L5.60659 4.66665L7.99992 7.05998L10.3933 4.66665L11.3333 5.60665L8.93992 7.99998L11.3333 10.3933Z" fill="black"/>
                            </g>
                            <defs>
                              <clipPath id="clip0_topic_{{ $topic->term_id }}">
                                <rect width="16" height="16" fill="white"/>
                              </clipPath>
                            </defs>
                          </svg>
                        </span>
                      </x-text>
                    </x-badge>
                  </button>
                </div>
              @endforeach
            </div>
          </template>

          {{-- Format Filters (shown when Formats tab is active) --}}
          <template x-if="activeTab === 'formats'">
            <div class="flex flex-wrap gap-3">
              @foreach($formats as $format)
                <div x-show="activeFilters.length === 0 || isFilterActive('{{ $format->term_id }}')">
                  <button @click="toggleFilter('{{ $format->term_id }}')" class="cursor-pointer">
                    <x-badge variant="default" size="sm" rounded="sm" class="bg-secondary-cyan min-h-[24px]">
                      <x-text
                        :as="TextTag::SPAN"
                        :size="TextSize::CAPTION"
                        class="text-primary-dark !flex items-center gap-3 !normal-case"
                      >
                        {{ $format->name }}
                        <span x-show="isFilterActive('{{ $format->term_id }}')">
                          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_format_{{ $format->term_id }}">
                              <path d="M7.99992 1.33331C4.31325 1.33331 1.33325 4.31331 1.33325 7.99998C1.33325 11.6866 4.31325 14.6666 7.99992 14.6666C11.6866 14.6666 14.6666 11.6866 14.6666 7.99998C14.6666 4.31331 11.6866 1.33331 7.99992 1.33331ZM11.3333 10.3933L10.3933 11.3333L7.99992 8.93998L5.60659 11.3333L4.66659 10.3933L7.05992 7.99998L4.66659 5.60665L5.60659 4.66665L7.99992 7.05998L10.3933 4.66665L11.3333 5.60665L8.93992 7.99998L11.3333 10.3933Z" fill="black"/>
                            </g>
                            <defs>
                              <clipPath id="clip0_format_{{ $format->term_id }}">
                                <rect width="16" height="16" fill="white"/>
                              </clipPath>
                            </defs>
                          </svg>
                        </span>
                      </x-text>
                    </x-badge>
                  </button>
                </div>
              @endforeach
            </div>
          </template>
        </div>
      </div>

      {{-- Loading State --}}
      <div x-show="loading" class="text-center py-12">
        <p class="text-neutral-500">Loading resources...</p>
      </div>

      {{-- Results --}}
      <div x-show="!loading">
        {{-- Featured Resources --}}
        <div
          x-show="featuredPosts.length > 0"
          :class="featuredPosts.length === 1 ? 'grid grid-cols-1 gap-6 mb-12' : 'grid grid-cols-1 gap-6 mb-12 md:grid-cols-2 md:grid-rows-[1fr] md:auto-rows-fr'"
        >
          <template x-for="post in featuredPosts" :key="post.id">
            <div class="flex" x-html="post.html"></div>
          </template>
        </div>

        {{-- Regular Resources Grid --}}
        <div x-show="posts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16 auto-rows-fr">
          <template x-for="post in posts" :key="post.id">
            <div class="flex" x-html="post.html"></div>
          </template>
        </div>

        {{-- No Results --}}
        <div x-show="posts.length === 0 && featuredPosts.length === 0" class="text-center py-12">
          <x-alert type="warning">
            Sorry, no resources were found.
          </x-alert>
        </div>

        {{-- Load More --}}
        <div x-show="hasMore && posts.length > 0" class="text-center">
          <button
            @click="page++; loadPosts();"
            class="px-6 py-3 bg-primary-green-neon text-primary-dark rounded-lg font-medium hover:opacity-90 transition-opacity"
          >
            Load More
          </button>
        </div>
      </div>
    </div>
  </x-container>
@endsection
