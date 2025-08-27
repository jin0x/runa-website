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

            <!-- L2 Submenu -->
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
        </li>
      @endforeach
    </ul>
  </nav>
@endif
