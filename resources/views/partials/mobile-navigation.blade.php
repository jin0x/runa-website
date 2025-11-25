@php
  use App\Enums\TextSize;
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
@endphp

@if ($navigation)
  <nav aria-label="{{ $name }}" id="{{ $slug }}-navigation" class="max-h-screen">
    <ul class="space-y-2">
      @foreach ($navigation as $item)
        <li x-data="{ open: false }" class="menu-item relative {{ $item->classes ?? '' }} {{ $item->active ? 'active' : '' }}">
          <a href="{{ $item->url }}"
             class="block px-3 py-2 text-base font-medium text-white hover:text-primary-green-neon transition-colors duration-200 !no-underline"
          >
            {{ $item->label }}
          </a>

          @if ($item->children)
            <!-- Arrow Button for Toggling Submenu -->
            <button @click="open = !open" class="absolute p-1 rounded-md right-0 top-0 mt-2 mr-3 hover:bg-white/10 text-white hover:text-primary-green-neon focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-green-neon transition-colors duration-200">
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
              <div class="mt-2 bg-primary-dark rounded-xl p-4"
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
                    <div class="py-3">
                      @if ($group['type'] === 'regular')
                        {{-- Regular Group --}}
                        <div class="bg-neutral-0-10 rounded-xl p-4">
                          <div class="flex items-center gap-3 mb-3">
                            @if (!empty($group['icon']['url']))
                              <img src="{{ $group['icon']['url'] }}" alt="{{ $group['icon']['alt'] ?? '' }}" class="w-6 h-6 flex-shrink-0">
                            @endif
                            @if (!empty($group['title']))
                              <x-heading
                                :as="HeadingTag::H4"
                                :size="HeadingSize::H6"
                                class="text-white"
                              >
                                {{ $group['title'] }}
                              </x-heading>
                            @endif
                          </div>

                          @if (!empty($group['items']))
                            <ul class="space-y-2">
                              @foreach ($group['items'] as $link)
                                <li>
                                  <a href="{{ $link['url'] ?? '#' }}"
                                    target="{{ $link['target'] ?? '_self' }}"
                                    class="group flex items-center justify-between px-3 py-2 rounded-lg hover:bg-neutral-400/10 transition-all duration-200 ease-in-out !no-underline">
                                    <div class="flex-1">
                                      <x-text :size="TextSize::BASE" class="text-white font-medium">
                                        {{ $link['label'] ?? '' }}
                                      </x-text>
                                      @if (!empty($link['description']))
                                        <x-text :size="TextSize::SMALL" class="text-neutral-400 mt-1">
                                          {{ $link['description'] }}
                                        </x-text>
                                      @endif
                                    </div>
                                    {{-- Hover Arrow --}}
                                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-3 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
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
                        {{-- Featured Group (Simplified for Mobile) --}}
                        <div class="bg-neutral-dark-10 rounded-xl overflow-hidden">
                          <div class="relative p-4 min-h-[200px] flex flex-col justify-center"
                               @if(!empty($group['background']['url']))
                                 style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.8) 100%), url('{{ $group['background']['url'] }}'); background-size: cover; background-position: center;"
                               @else
                                 style="background: linear-gradient(135deg, #2d2d2d 0%, #3d3d3d 100%);"
                               @endif
                          >
                            @if (!empty($group['title']))
                              <x-heading
                                :as="HeadingTag::H3"
                                :size="HeadingSize::H5"
                                class="text-white mb-2"
                              >
                                {{ $group['title'] }}
                              </x-heading>
                            @endif
                            @if (!empty($group['description']))
                              <x-text :size="TextSize::BASE" class="text-neutral-200 mb-4">
                                {{ $group['description'] }}
                              </x-text>
                            @endif
                            @if (!empty($group['cta']['url']))
                              <a href="{{ $group['cta']['url'] }}"
                                target="{{ $group['cta']['target'] ?? '_self' }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-primary-green-neon text-black rounded-lg hover:bg-primary-green-soft transition-all">
                                {{ $group['cta']['label'] ?? 'Learn More' }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                              </a>
                            @endif
                          </div>
                        </div>
                      @endif
                    </div>
                  @endforeach
                @endif

                @if (!empty($item->callout))
                  {{-- Callout Section - Mobile Optimized and Clickable --}}
                  <div class="mt-3">
                    @if (!empty($item->callout['cta']['url']))
                      {{-- Entire section is clickable --}}
                      <a href="{{ $item->callout['cta']['url'] }}"
                         target="{{ $item->callout['cta']['target'] ?? '_self' }}"
                         class="group block bg-neutral-dark-10 rounded-xl p-4 hover:bg-neutral-400/10 transition-all duration-200 cursor-pointer !no-underline"
                      >
                        <div class="flex items-center justify-between">
                          <div class="flex-1">
                            @if (!empty($item->callout['title']))
                              <x-text :size="TextSize::BASE" class="text-white font-medium">{{ $item->callout['title'] }}</x-text>
                            @endif
                            @if (!empty($item->callout['description']))
                              <x-text :size="TextSize::SMALL" class="text-neutral-400 mt-1">{{ $item->callout['description'] }}</x-text>
                            @endif
                          </div>
                          {{-- Hover Arrow --}}
                          <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            <g clip-path="url(#clip0_3657_9862)">
                              <path d="M7.99984 4.66675L7.05984 5.60675L10.7798 9.33342H2.6665V10.6667H10.7798L7.05984 14.3934L7.99984 15.3334L13.3332 10.0001L7.99984 4.66675Z" fill="#00FFA3"/>
                            </g>
                            <defs>
                              <clipPath id="clip0_3657_9862">
                                <rect width="16" height="16" fill="white" transform="translate(0 2)"/>
                              </clipPath>
                            </defs>
                          </svg>
                        </div>
                      </a>
                    @else
                      {{-- Fallback: No CTA, not clickable --}}
                      <div class="bg-neutral-dark-10 rounded-xl p-4">
                        @if (!empty($item->callout['title']))
                          <x-text :size="TextSize::BASE" class="text-white font-medium">{{ $item->callout['title'] }}</x-text>
                        @endif
                        @if (!empty($item->callout['description']))
                          <x-text :size="TextSize::SMALL" class="text-neutral-400 mt-1">{{ $item->callout['description'] }}</x-text>
                        @endif
                      </div>
                    @endif
                  </div>
                @endif

              </div>

            @elseif (!$is_mega_menu && is_array($item->children))
              {{-- Standard WordPress Menu Mobile Layout --}}
              <ul class="child-menu space-y-1 mt-2 bg-primary-dark rounded-xl p-3"
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
                    <a href="{{ $child->url }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-primary-green-neon transition-colors duration-200">
                      {{ $child->label }}
                    </a>

                    @if ($child->children)
                      <!-- Arrow Button for Toggling L3 Submenu -->
                      <button @click="open = !open" class="absolute p-1 rounded-md right-0 top-0 mt-2 mr-3 hover:bg-white/10 text-white hover:text-primary-green-neon focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-green-neon transition-colors duration-200">
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
                      <ul class="child-menu space-y-1 pl-4 mt-2 bg-neutral-700/30 rounded-lg p-2"
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
                            <a href="{{ $grandchild->url }}" class="block px-3 py-2 text-base font-medium text-white hover:text-primary-green-neon transition-colors duration-200">
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
