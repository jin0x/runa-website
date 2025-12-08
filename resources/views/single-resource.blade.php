@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
@endphp

@extends('layouts.app')

@section('content')
  @while(have_posts()) 
    @php the_post() @endphp
    
    @php
      // Check if this resource is a Guide
      $categories = get_the_terms(get_the_ID(), 'resource_category');
      $isGuide = false;
      $categoryNames = [];
      
      if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $category) {
          $categoryNames[] = strtolower($category->name);
          if (strtolower($category->name) === 'guides') {
            $isGuide = true;
            break;
          }
        }
      }
    @endphp

    @if($isGuide)
      {{-- GUIDE LAYOUT --}}
      <article class="resource-single resource-guide">
        @php
          // Parse blocks to separate Hero from Guide Sections
          $postContent = get_the_content();
          $blocks = parse_blocks($postContent);
          $heroBlocks = [];
          $guideSectionBlocks = [];
          $tocItems = [];
          
          foreach ($blocks as $block) {
            if ($block['blockName'] === 'acf/hero') {
              $heroBlocks[] = $block;
            } elseif ($block['blockName'] === 'acf/guide-section') {
              $guideSectionBlocks[] = $block;
              
              // Build TOC from Guide Section blocks - access ACF data correctly
              $blockData = $block['attrs']['data'] ?? [];
              $tocText = $blockData['toc_text'] ?? '';
              
              if ($tocText) {
                $anchorId = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tocText)));
                $tocItems[] = [
                  'text' => $tocText,
                  'anchor' => $anchorId
                ];
              }
            }
          }
          
          $downloadLink = get_field('guide_download_link');
        @endphp

        {{-- Hero Section - Full Width --}}
        @if(!empty($heroBlocks))
          <div class="guide-hero">
            @foreach($heroBlocks as $heroBlock)
              {!! render_block($heroBlock) !!}
            @endforeach
          </div>
        @endif

        {{-- Guide Content with TOC - Constrained Width --}}
        @if(!empty($guideSectionBlocks) || !empty($tocItems))
          <div class="guide-content-wrapper max-w-7xl mx-auto px-16 py-16">
            <div class="flex">
              {{-- Table of Contents - Sticky Sidebar --}}
              <aside class="guide-toc w-86 flex-shrink-0 pr-12 sticky top-8 self-start">
                <div class="toc-container">
                  <x-heading
                    :as="HeadingTag::H3"
                    :size="HeadingSize::H6_BOLD"
                    class="uppercase mb-12 tracking-wider"
                  >
                    Table of Contents
                  </x-heading>
                  
                  @if(!empty($tocItems))
                    <nav class="toc-nav">
                      <ul class="toc-list space-y-3">
                        @foreach($tocItems as $item)
                          <li class="toc-item">
                            <a href="#{{ $item['anchor'] }}" class="toc-link flex items-center justify-between text-xs py-1 !no-underline">
                              <x-text :size="TextSize::MEDIUM">{{ $item['text'] }}</x-text>
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_4852_2584)">
                                 <path d="M9.99992 3.33337L8.82492 4.50837L13.4749 9.16671H3.33325V10.8334H13.4749L8.82492 15.4917L9.99992 16.6667L16.6666 10L9.99992 3.33337Z" fill="black"/>
                                </g>
                                <defs>
                                  <clipPath id="clip0_4852_2584">
                                    <rect width="20" height="20" fill="white"/>
                                  </clipPath>
                                </defs>
                              </svg>
                            </a>
                          </li>
                        @endforeach
                      </ul>
                    </nav>
                  @endif

                  {{-- Download CTA --}}
                  @if($downloadLink)
                    <div class="toc-cta mt-12">
                      <a href="{{ $downloadLink }}" target="_blank" class="inline-flex items-center gap-3 bg-primary-green-neon text-primary-dark px-6 py-3 rounded-lg font-medium hover:bg-primary-green-neon/90 transition-colors !no-underline">
                        <x-text :size="TextSize::SMALL">Download PDF</x-text>
                      </a>
                    </div>
                  @endif
                </div>
              </aside>

              {{-- Main Content --}}
              <main class="guide-main-content flex-1 ml-6">
                <div class="guide-content">
                  @foreach($guideSectionBlocks as $block)
                    {!! render_block($block) !!}
                  @endforeach
                </div>
              </main>
            </div>
          </div>
        @endif
      </article>
    @else
      {{-- STANDARD RESOURCE LAYOUT --}}
      <article class="resource-single">
        <div class="max-w-none">
          @php(the_content())
        </div>
      </article>
    @endif
  @endwhile

  @if($isGuide)
    {{-- JavaScript for Smooth Scrolling --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Smooth scrolling for TOC links
      const tocLinks = document.querySelectorAll('.toc-link');
      
      tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').substring(1);
          const targetElement = document.getElementById(targetId);
          
          if (targetElement) {
            targetElement.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
    });
    </script>

    {{-- CSS for Guide Styling --}}
    <style>
    .guide-content {
      /* Enhanced prose styling for guides */
    }
    
    .guide-content img {
      border-radius: 12px;
    }
    
    .guide-content blockquote {
      background: #4ADE80;
      color: #1F2937;
      border-radius: 16px;
      padding: 2rem;
      border: none;
      margin: 2rem 0;
      font-weight: 500;
    }
    
    .guide-content ul {
      list-style: none;
      padding-left: 0;
    }
    
    .guide-content ul li {
      position: relative;
      padding-left: 1.5rem;
      margin-bottom: 0.5rem;
    }
    
    .guide-content ul li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: #4ADE80;
      font-weight: bold;
    }
    
    .toc-container {
      padding: 2rem;
      height: fit-content;
    }
    
    .toc-link.active {
      color: #4ADE80;
      font-weight: 600;
    }
    </style>
  @endif
@endsection