@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
  use App\Enums\TextColor;
@endphp

@if ($navigation)
  <nav x-data="navigation" aria-label="{{ $name }}" id="{{ $slug }}-navigation" class="px-10 bg-gradient-3 rounded-full">
    <ul class="flex gap-2">
      @foreach ($navigation as $item)
        <li class="menu-item relative {{ $item->classes ?? '' }} {{ $item->active ? 'active' : '' }}">
          <div class="flex items-center justify-center h-16 p-2 gap-1 min-h-[40px] min-w-[105px]">
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
              <button
                @click="toggleDropdown($event, '{{ $loop->index }}')"
                @keydown.escape.window="closeDropdown()"
                class="rounded-md bg-transparent text-white hover:text-primary-green-neon focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-green-neon"
                aria-expanded="false"
              >
                <span class="sr-only">Toggle dropdown</span>
                <svg x-show="activeDropdown !== '{{ $loop->index }}'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
                <svg x-show="activeDropdown === '{{ $loop->index }}'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>
                </svg>
              </button>
            @endif
          </div>

@if ($item->children)
  <!-- Dropdown Panel -->
  <div class="w-full absolute left-0"
      x-show="activeDropdown === '{{ $loop->index }}'"
      @click.away="closeDropdown()"
      x-cloak
      x-transition:enter="transition ease-out duration-300 ease-out"
      x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100"
      x-transition:leave-end="opacity-0 transform scale-95">
    
    @if ($is_mega_menu && $item->has_submenu)
      {{-- Mega Menu Layout --}}
      <div class="mt-2 bg-primary-dark p-8 rounded-bl-xl rounded-br-xl mx-auto max-w-fit">
        
        @if (!empty($item->submenu_groups))
          <div class="grid grid-cols-{{ count($item->submenu_groups) }} gap-8 mb-6">
            @foreach ($item->submenu_groups as $group)
              <div class="min-w-[200px]">
                @if ($group['type'] === 'regular')
                  {{-- Regular Group --}}
                  <div class="flex items-center gap-2 mb-4">
                    @if (!empty($group['icon']['url']))
                      <img src="{{ $group['icon']['url'] }}" alt="{{ $group['icon']['alt'] ?? '' }}" class="w-6 h-6">
                    @endif
                    @if (!empty($group['title']))
                      <h3 class="text-base font-medium text-white">{{ $group['title'] }}</h3>
                    @endif
                  </div>
                  
                  @if (!empty($group['items']))
                    <ul class="space-y-3">
                      @foreach ($group['items'] as $link)
                        <li>
                          <a href="{{ $link['url'] ?? '#' }}" target="{{ $link['target'] ?? '_self' }}" class="block text-white hover:text-primary-green-neon">
                            <div class="font-medium">{{ $link['label'] ?? '' }}</div>
                            @if (!empty($link['description']))
                              <div class="text-sm text-neutral-400 mt-1">{{ $link['description'] }}</div>
                            @endif
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                  
                @elseif ($group['type'] === 'featured')
                  {{-- Featured Group --}}
                  <div class="relative rounded-lg overflow-hidden p-6 min-h-[200px] flex flex-col justify-end"
                       @if(!empty($group['background']['url']))
                         style="background-image: url('{{ $group['background']['url'] }}'); background-size: cover; background-position: center;"
                       @endif
                  >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    <div class="relative z-10">
                      @if (!empty($group['title']))
                        <h3 class="text-xl font-bold text-white mb-2">{{ $group['title'] }}</h3>
                      @endif
                      @if (!empty($group['description']))
                        <p class="text-sm text-neutral-200 mb-4">{{ $group['description'] }}</p>
                      @endif
                      @if (!empty($group['cta']['url']))
                        <a href="{{ $group['cta']['url'] }}" 
                           target="{{ $group['cta']['target'] ?? '_self' }}"
                           class="inline-block px-4 py-2 bg-primary-green-neon text-black rounded-md hover:bg-primary-green-soft transition">
                          {{ $group['cta']['label'] ?? 'Learn More' }}
                        </a>
                      @endif
                    </div>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        @endif

        @if (!empty($item->callout))
          {{-- Callout Section --}}
          <div class="border-t border-neutral-700 pt-6">
            <div class="relative rounded-lg overflow-hidden p-6"
                 @if(!empty($item->callout['background']['url']))
                   style="background-image: url('{{ $item->callout['background']['url'] }}'); background-size: cover; background-position: center;"
                 @endif
            >
              <div class="absolute inset-0 bg-black/60"></div>
              <div class="relative z-10 flex items-center justify-between">
                <div>
                  @if (!empty($item->callout['title']))
                    <h4 class="text-lg font-bold text-white">{{ $item->callout['title'] }}</h4>
                  @endif
                  @if (!empty($item->callout['description']))
                    <p class="text-sm text-neutral-200">{{ $item->callout['description'] }}</p>
                  @endif
                </div>
                @if (!empty($item->callout['cta']['url']))
                  <a href="{{ $item->callout['cta']['url'] }}" 
                     target="{{ $item->callout['cta']['target'] ?? '_self' }}"
                     class="px-4 py-2 bg-white text-black rounded-md hover:bg-neutral-100 transition whitespace-nowrap">
                    {{ $item->callout['cta']['label'] ?? 'Learn More' }}
                  </a>
                @endif
              </div>
            </div>
          </div>
        @endif

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
      toggleDropdown( event, id ) {
        event.stopPropagation();
        this.activeDropdown = this.activeDropdown === id ? null : id;
      },
      closeDropdown() {
        this.activeDropdown = null;
      },
      init() {
        window.addEventListener( 'scroll', () => {
          this.closeDropdown();
        } );
      }
    }) );
  } );
</script>