@if ($navigation)
  <nav x-data="navigation" aria-label="{{ $name }}" id="{{ $slug }}-navigation" class="px-2 pt-2 pb-3">
    <ul class="flex md:space-x-4">
      @foreach ($navigation as $item)
        <li class="menu-item relative {{ $item->classes ?? '' }} {{ $item->active ? 'active' : '' }}">
          <div class="flex items-center">
            <a
              href="{{ $item->url }}"
              class="block px-3 py-2 rounded-md text-base font-medium text-white hover:text-primary-lime "
            >
              {{ $item->label }}
            </a>
            @if ($item->children)
              <button
                @click="toggleDropdown($event, '{{ $loop->index }}')"
                @keydown.escape.window="closeDropdown()"
                class="ml-1 p-1 rounded-md bg-transparent text-white hover:text-primary-lime focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
            <!-- L2 Submenu -->
            <div class="w-full absolute left-0"
                x-show="activeDropdown === '{{ $loop->index }}'"
                @click.away="closeDropdown()"
                x-cloak
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
              <ul class="
              {{ get_field('simple_menu', $item->id) ? 'min-w-fit lg:flex-col lg:gap-2' : '' }}
              mt-2 bg-primary-dark  p-8 flex flex-col gap-8 mx-auto max-w-fit rounded-bl-xl rounded-br-xl">
                @foreach ($item->children as $child)
                  <li class="min-w-[200px] {{ $child->classes ?? '' }} {{ $child->active ? 'active' : '' }}">
                    <a href="{{ $child->url }}" class="block text-base font-medium text-white hover:text-primary-lime mb-2">
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
