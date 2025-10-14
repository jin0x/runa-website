@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\TextColor;

  use App\Enums\HeadingSize;
  use App\Enums\HeadingTag;

  use App\Enums\ButtonSize;
  use App\Enums\ButtonVariant;

@endphp

@if ($navigation)
  <nav x-data="navigation" aria-label="{{ $name }}" id="{{ $slug }}-navigation" class="px-10 bg-gradient-3 rounded-full">
    <ul class="flex gap-2">
      @foreach ($navigation as $item)
        <li class="menu-item relative {{ $item->classes ?? '' }} {{ $item->active ? 'active' : '' }}"
            @if($item->children)
              @mouseenter="toggleDropdown($event, '{{ $loop->index }}')"
              @mouseleave="closeDropdown()"
            @endif
        >
          <div class="flex items-center justify-center h-16 p-2 gap-3 min-h-[40px] min-w-[105px]      relative">
            {{-- Active state bottom border --}}
            @if($item->active)
              <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-primary-green-neon"></div>
            @endif

            <x-text
              :size="TextSize::BASE"
              :as="TextTag::A"
              :color="TextColor::DARK"
              href="{{ $item->url }}"
              class="block hover:text-primary-green-neon transition-colors duration-200 !no-underline"
            >
              {{ $item->label }}
            </x-text>

            @if ($item->children)
              {{-- Chevron (visual only, hover works on entire li) --}}
              <div class="pointer-events-none">
                <svg x-show="activeDropdown !== '{{ $loop->index }}'" xmlns="http://www.w3.org/2000/svg"      width="13" height="8" viewBox="0 0 13 8" fill="none" class="transition-transform      duration-200 rotate-180">
                  <path d="M6.5 0L0.5 6L1.91 7.41L6.5 2.83L11.09 7.41L12.5 6L6.5 0Z" fill="white"/>
                </svg>
                <svg x-show="activeDropdown === '{{ $loop->index }}'" xmlns="http://www.w3.org/2000/svg"      width="13" height="8" viewBox="0 0 13 8" fill="none" class="transition-transform      duration-200">
                  <path d="M6.5 0L0.5 6L1.91 7.41L6.5 2.83L11.09 7.41L12.5 6L6.5 0Z" fill="white"/>
                </svg>
              </div>
            @endif
          </div>

@if ($item->children)
  <!-- Dropdown Panel -->
  <div class="w-full absolute left-0"
      x-show="activeDropdown === '{{ $loop->index }}'"
      @mouseenter="cancelClose()"
      @mouseleave="closeDropdown()"
      x-cloak
      x-transition:enter="transition ease-out duration-300 ease-out"
      x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100"
      x-transition:leave-end="opacity-0 transform scale-95">
    
@if ($is_mega_menu && $item->has_submenu)
  {{-- Mega Menu Layout --}}
  @php
    // Determine width based on content
    $groupCount = count($item->submenu_groups ?? []);
    $hasFeatured = collect($item->submenu_groups ?? [])->contains('type', 'featured');
    
    if ($groupCount === 1) {
        $widthClass = 'w-[400px]'; // Single group - narrow
    } elseif ($hasFeatured) {
        $widthClass = 'w-[1000px]'; // Regular + Featured - wider
    } else {
        $widthClass = 'w-[800px]'; // Two regular groups - medium
    }
  @endphp

  <div class="mt-3 bg-primary-dark rounded-xl {{ $widthClass }} left-1/2 -translate-x-1/2">
    <div class="px-8 py-8">
      
      @if (!empty($item->submenu_groups))
        <div class="grid {{ count($item->submenu_groups) === 2 ? 'grid-cols-2' : 'grid-cols-1' }} gap-3">
          @foreach ($item->submenu_groups as $group)
            @if ($group['type'] === 'regular')
              {{-- Regular Group with Gray Box --}}
              <div class="bg-neutral-dark-10 rounded-xl p-6">
                <div class="flex items-center gap-6 mb-4">
                  @if (!empty($group['icon']['url']))
                    <img src="{{ $group['icon']['url'] }}" alt="{{ $group['icon']['alt'] ?? '' }}" class="w-10 h-10 flex-shrink-0">
                  @endif
                  @if (!empty($group['title']))
                    <x-heading :as="HeadingTag::H4" :size="HeadingSize::H4" :color="TextColor::DARK">{{ $group['title'] }}</x-heading>
                  @endif
                </div>
                
                @if (!empty($group['items']))
                  <ul class="space-y-4">
                    @foreach ($group['items'] as $link)
                      <li>
                        <a href="{{ $link['url'] ?? '#' }}" 
                           target="{{ $link['target'] ?? '_self' }}" 
                           class="group flex items-center justify-between">
                          <div class="flex-1">
                            <x-text :size="TextSize::MEDIUM_BOLD" :color="TextColor::DARK"  class="group-hover:text-primary-green-neon transition-colors">
                              {{ $link['label'] ?? '' }}
                            </x-text>
                            @if (!empty($link['description']))
                              <div class="text-sm text-neutral-400 mt-1 group-hover:text-neutral-300 transition-colors">
                                {{ $link['description'] }}
                              </div>
                            @endif
                          </div>
                          {{-- Hover Arrow (right side, centered) --}}
                          <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-4 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            <g clip-path="url(#clip0_3657_9862)">
                              <path d="M7.99984 4.66675L7.05984 5.60675L10.7798 9.33342H2.6665V10.6667H10.7798L7.05984 14.3934L7.99984 15.3334L13.3332 10.0001L7.99984 4.66675Z" fill="#00FFA3"/>
                            </g>
                            <defs>
                              <clipPath id="clip0_3657_9862">
                                <rect width="16" height="16" fill="white" transform="translate(0 2)"/>
                              </clipPath>
                            </defs>
                          </svg>
                        </a>
                      </li>
                    @endforeach
                  </ul>
                @endif
              </div>
              
            @elseif ($group['type'] === 'featured')
              {{-- Featured Group --}}
              <div class="bg-neutral-dark-10 rounded-xl">
                <div class="relative rounded-xl overflow-hidden min-h-[280px] flex flex-col justify-start group"
                     @if(!empty($group['background']['url']))
                       style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.8) 100%), url('{{ $group['background']['url'] }}'); background-size: cover; background-position: center;"
                     @else
                       style="background: linear-gradient(135deg, #2d2d2d 0%, #3d3d3d 100%);"
                     @endif
                >
                  <div class="relative z-10 p-6">
                    @if (!empty($group['title']))
                      <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-primary-green-neon transition-colors">
                        {{ $group['title'] }}
                      </h3>
                    @endif
                    @if (!empty($group['description']))
                      <p class="text-base text-neutral-200 mb-6 leading-relaxed">
                        {{ $group['description'] }}
                      </p>
                    @endif
                    @if (!empty($group['cta']['url']))
                      <a href="{{ $group['cta']['url'] }}" 
                         target="{{ $group['cta']['target'] ?? '_self' }}"
                         class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-green-neon text-black font-medium rounded-lg hover:bg-primary-green-soft transition-all transform hover:scale-105">
                        {{ $group['cta']['label'] ?? 'Learn More' }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>
      @endif

      @if (!empty($item->callout) && !empty($item->callout['title']))
        {{-- Callout Section --}}
        <div class="pt-3">
          <div class="relative rounded-xl overflow-hidden bg-neutral-dark-10"
               @if(!empty($item->callout['background']['url']))
                 style="background-image: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 100%), url('{{ $item->callout['background']['url'] }}'); background-size: cover; background-position: center;"
               @endif
          >
            <div class="relative z-10 flex items-center justify-between p-6">
              <div class="flex-1">
                @if (!empty($item->callout['title']))
                  <x-text :size="TextSize::MEDIUM" :color="TextColor::DARK" class="mb-1">{{ $item->callout['title'] }}</x-text>
                @endif
                @if (!empty($item->callout['description']))
                  <p class="text-sm text-neutral-300">{{ $item->callout['description'] }}</p>
                @endif
              </div>
              @if (!empty($item->callout['cta']['url']))
                <a href="{{ $item->callout['cta']['url'] }}" 
                   target="{{ $item->callout['cta']['target'] ?? '_self' }}"
                   class="ml-6 px-6 py-3 bg-white text-black font-medium rounded-lg hover:bg-neutral-100 transition-all whitespace-nowrap">
                  {{ $item->callout['cta']['label'] ?? 'Learn More' }}
                </a>
              @endif
            </div>
          </div>
        </div>
      @endif

    </div>
  </div>
    @elseif (!$is_mega_menu && is_array($item->children))
      {{-- Standard WordPress Menu Layout --}}
      <ul class="
      @if($item->id && get_field('simple_menu', $item->id))
        min-w-fit lg:flex-col lg:gap-2
      @endif
      mt-2 bg-primary-dark p-8 flex flex-col gap-8 mx-auto max-w-fit rounded-bl-xl rounded-br-xl">
        @foreach ($item->children as $child)
          <li class="min-w-[200px] {{ $child->classes ?? '' }} {{ $child->active ? 'active' : '' }}">
            <a href="{{ $child->url }}" class="block text-base font-medium text-white hover:text-primary-green-neon mb-2">
              {{ $child->label }}
            </a>

            @if ($child->children)
              <!-- L3 Items  -->
              <ul class="space-y-1 border-t border-neutral-200 pt-4 mt-2">
                @foreach ($child->children as $grandchild)
                  <li class="grandchild-item {{ $grandchild->classes ?? '' }} {{ $grandchild->active ? 'active' : '' }}">
                    <a href="{{ $grandchild->url }}" class="block py-1 text-sm text-neutral-600 hover:text-primary-orange">
                      {{ $grandchild->label }}
                    </a>
                  </li>
                @endforeach
              </ul>
            @endif
          </li>
        @endforeach
      </ul>
    @endif

  </div>
@endif
</li>
      @endforeach
    </ul>
  </nav>
@endif

<script>
  document.addEventListener( 'alpine:init', () => {
    Alpine.data( 'navigation', () => ({
      activeDropdown: null,
      closeTimeout: null,
      toggleDropdown( event, id ) {
        // Clear any pending close timeout immediately
        if (this.closeTimeout) {
          clearTimeout(this.closeTimeout);
          this.closeTimeout = null;
        }
        // Set the new active dropdown
        this.activeDropdown = id;
      },
      closeDropdown() {
        // Add small delay before closing
        this.closeTimeout = setTimeout(() => {
          this.activeDropdown = null;
          this.closeTimeout = null;
        }, 150);
      },
      cancelClose() {
        // Cancel any pending close
        if (this.closeTimeout) {
          clearTimeout(this.closeTimeout);
          this.closeTimeout = null;
        }
      },
      init() {
        // Close dropdown on scroll
        window.addEventListener( 'scroll', () => {
          if (this.closeTimeout) {
            clearTimeout(this.closeTimeout);
            this.closeTimeout = null;
          }
          this.activeDropdown = null;
        } );
        
        // Close dropdown on ESC key
        window.addEventListener( 'keydown', (e) => {
          if (e.key === 'Escape') {
            if (this.closeTimeout) {
              clearTimeout(this.closeTimeout);
              this.closeTimeout = null;
            }
            this.activeDropdown = null;
          }
        });
      }
    }) );
  } );
</script>