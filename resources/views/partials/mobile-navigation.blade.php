@if ($navigation)
  <nav aria-label="{{ $name }}" id="{{ $slug }}-navigation" class="px-2 pt-2 pb-3">
    <ul class="lg:space-x-10">
      @foreach ($navigation as $item)
        <li x-data="{ open: false }" class="menu-item relative {{ $item->classes ?? '' }} {{ $item->active ? 'active' : '' }}">
          <a href="{{ $item->url }}"
             class="block px-3 py-2 text-base font-medium text-white hover:text-primary-lime"
          >
            {{ $item->label }}
          </a>

          @if ($item->children)
            <!-- Arrow Button for Toggling Submenu -->
            <button @click="open = !open" class="absolute p-1 rounded-md right-0 top-0 mt-2 mr-3 hover:bg-gray-100 text-white hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-navy">
              <span x-show="!open">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
              </span>
              <span x-show="open">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>
                </svg>
              </span>
            </button>

            @if ($is_mega_menu && $item->has_submenu)
              {{-- Mega Menu Mobile Layout --}}
              <div class="pl-4"
                  x-show="open"
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="opacity-0 transform scale-95"
                  x-transition:enter-end="opacity-100 transform scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="opacity-100 transform scale-100"
                  x-transition:leave-end="opacity-0 transform scale-95"
                  style="display: none;">
                
                @if (!empty($item->submenu_groups))
                  @foreach ($item->submenu_groups as $group)
                    <div class="py-2 border-b border-neutral-700 last:border-b-0">
                      @if ($group['type'] === 'regular')
                        {{-- Regular Group --}}
                        <div class="flex items-center gap-2 mb-2">
                          @if (!empty($group['icon']['url']))
                            <img src="{{ $group['icon']['url'] }}" alt="{{ $group['icon']['alt'] ?? '' }}" class="w-5 h-5">
                          @endif
                          @if (!empty($group['title']))
                            <div class="text-sm font-semibold text-white">{{ $group['title'] }}</div>
                          @endif
                        </div>
                        
                        @if (!empty($group['items']))
                          <ul class="space-y-1">
                            @foreach ($group['items'] as $link)
                              <li>
                                <a href="{{ $link['url'] ?? '#' }}" 
                                   target="{{ $link['target'] ?? '_self' }}"
                                   class="block px-3 py-2 text-base font-medium text-white hover:text-primary-lime">
                                  {{ $link['label'] ?? '' }}
                                  @if (!empty($link['description']))
                                    <span class="block text-xs text-neutral-400 mt-1">{{ $link['description'] }}</span>
                                  @endif
                                </a>
                              </li>
                            @endforeach
                          </ul>
                        @endif
                        
                      @elseif ($group['type'] === 'featured')
                        {{-- Featured Group (Simplified for Mobile) --}}
                        <div class="p-3 bg-neutral-800 rounded">
                          @if (!empty($group['title']))
                            <div class="text-base font-bold text-white mb-1">{{ $group['title'] }}</div>
                          @endif
                          @if (!empty($group['description']))
                            <p class="text-sm text-neutral-300 mb-3">{{ $group['description'] }}</p>
                          @endif
                          @if (!empty($group['cta']['url']))
                            <a href="{{ $group['cta']['url'] }}" 
                               target="{{ $group['cta']['target'] ?? '_self' }}"
                               class="inline-block px-4 py-2 bg-primary-green-neon text-black rounded-md text-sm font-medium">
                              {{ $group['cta']['label'] ?? 'Learn More' }}
                            </a>
                          @endif
                        </div>
                      @endif
                    </div>
                  @endforeach
                @endif

                @if (!empty($item->callout))
                  {{-- Callout Section --}}
                  <div class="mt-3 p-3 bg-neutral-800 rounded">
                    @if (!empty($item->callout['title']))
                      <div class="text-base font-bold text-white mb-1">{{ $item->callout['title'] }}</div>
                    @endif
                    @if (!empty($item->callout['description']))
                      <p class="text-sm text-neutral-300 mb-3">{{ $item->callout['description'] }}</p>
                    @endif
                    @if (!empty($item->callout['cta']['url']))
                      <a href="{{ $item->callout['cta']['url'] }}" 
                         target="{{ $item->callout['cta']['target'] ?? '_self' }}"
                         class="inline-block px-4 py-2 bg-white text-black rounded-md text-sm font-medium">
                        {{ $item->callout['cta']['label'] ?? 'Learn More' }}
                      </a>
                    @endif
                  </div>
                @endif

              </div>

            @elseif (!$is_mega_menu && is_array($item->children))
              {{-- Standard WordPress Menu Mobile Layout --}}
              <ul class="child-menu space-y-1 pl-4"
                  x-show="open"
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="opacity-0 transform scale-95"
                  x-transition:enter-end="opacity-100 transform scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="opacity-100 transform scale-100"
                  x-transition:leave-end="opacity-0 transform scale-95"
                  style="display: none;"
              >
                @foreach ($item->children as $child)
                  <li x-data="{ open: false }" class="child-item relative {{ $child->classes ?? '' }} {{ $child->active ? 'active' : '' }}">
                    <a href="{{ $child->url }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-primary-lime">
                      {{ $child->label }}
                    </a>

                    @if ($child->children)
                      <!-- Arrow Button for Toggling L3 Submenu -->
                      <button @click="open = !open" class="absolute p-1 rounded-md right-0 top-0 mt-2 mr-3 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-navy">
                        <span x-show="!open">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                          </svg>
                        </span>
                        <span x-show="open">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>
                          </svg>
                        </span>
                      </button>

                      <!-- L3 Submenu -->
                      <ul class="child-menu space-y-1 pl-4"
                          x-show="open"
                          x-transition:enter="transition ease-out duration-100"
                          x-transition:enter-start="opacity-0 transform scale-95"
                          x-transition:enter-end="opacity-100 transform scale-100"
                          x-transition:leave="transition ease-in duration-75"
                          x-transition:leave-start="opacity-100 transform scale-100"
                          x-transition:leave-end="opacity-0 transform scale-95"
                          style="display: none;"
                      >
                        @foreach ($child->children as $grandchild)
                          <li class="grandchild-item {{ $grandchild->classes ?? '' }} {{ $grandchild->active ? 'active' : '' }}">
                            <a href="{{ $grandchild->url }}" class="block px-3 py-2 text-base font-medium text-white hover:text-primary-lime">
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

          @endif
        </li>
      @endforeach
    </ul>
  </nav>
@endif