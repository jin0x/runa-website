@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'src' => '', // Video source URL
    'poster' => '', // Poster image URL
    'title' => '', // Video title
    'description' => '', // Video description
    'autoplay' => false, // Auto-play video
    'muted' => false, // Start muted
    'loop' => false, // Loop video
    'controls' => true, // Show native controls
    'customControls' => false, // Use custom controls
    'aspectRatio' => '16/9', // Aspect ratio (16/9, 4/3, 1/1)
    'maxWidth' => null, // Max width
    'class' => '',
])

@php
  // Generate unique ID for this video player
  $playerId = 'video-' . uniqid();

  // Define aspect ratio classes
  $aspectClasses = match($aspectRatio) {
      '4/3' => 'aspect-[4/3]',
      '1/1' => 'aspect-square',
      default => 'aspect-video', // 16/9
  };

  // Validate video source
  if (empty($src)) {
      return;
  }
@endphp

<div 
  x-data="{
    playing: false,
    muted: {{ $muted ? 'true' : 'false' }},
    volume: 1,
    currentTime: 0,
    duration: 0,
    fullscreen: false,
    showControls: true,
    controlsTimeout: null,
    
    init() {
      this.\$refs.video.addEventListener('loadedmetadata', () => {
        this.duration = this.\$refs.video.duration;
      });
      
      this.\$refs.video.addEventListener('timeupdate', () => {
        this.currentTime = this.\$refs.video.currentTime;
      });
      
      this.\$refs.video.addEventListener('play', () => {
        this.playing = true;
      });
      
      this.\$refs.video.addEventListener('pause', () => {
        this.playing = false;
      });
      
      this.\$refs.video.addEventListener('volumechange', () => {
        this.volume = this.\$refs.video.volume;
        this.muted = this.\$refs.video.muted;
      });
    },
    
    togglePlay() {
      if (this.playing) {
        this.\$refs.video.pause();
      } else {
        this.\$refs.video.play();
      }
    },
    
    toggleMute() {
      this.\$refs.video.muted = !this.\$refs.video.muted;
    },
    
    setVolume(value) {
      this.\$refs.video.volume = value;
    },
    
    seek(time) {
      this.\$refs.video.currentTime = time;
    },
    
    seekToPercent(percent) {
      this.\$refs.video.currentTime = (percent / 100) * this.duration;
    },
    
    toggleFullscreen() {
      if (!document.fullscreenElement) {
        this.\$refs.container.requestFullscreen();
        this.fullscreen = true;
      } else {
        document.exitFullscreen();
        this.fullscreen = false;
      }
    },
    
    formatTime(seconds) {
      const mins = Math.floor(seconds / 60);
      const secs = Math.floor(seconds % 60);
      return mins + ':' + (secs < 10 ? '0' : '') + secs;
    },
    
    showControlsTemporarily() {
      this.showControls = true;
      clearTimeout(this.controlsTimeout);
      this.controlsTimeout = setTimeout(() => {
        if (this.playing) {
          this.showControls = false;
        }
      }, 3000);
    }
  }"
  x-ref="container"
  @mousemove="showControlsTemporarily()"
  @click="{{ $customControls ? 'togglePlay()' : '' }}"
  class="relative bg-black rounded-lg overflow-hidden {{ $class }} {{ $maxWidth ? 'max-w-[' . $maxWidth . ']' : '' }}"
>
  {{-- Video Element --}}
  <video
    x-ref="video"
    class="w-full h-full {{ $aspectClasses }}"
    {{ $poster ? 'poster=' . $poster : '' }}
    {{ $autoplay ? 'autoplay' : '' }}
    {{ $muted ? 'muted' : '' }}
    {{ $loop ? 'loop' : '' }}
    {{ $controls && !$customControls ? 'controls' : '' }}
    playsinline
  >
    <source src="{{ $src }}" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  {{-- Custom Controls Overlay --}}
  @if($customControls)
    <div 
      x-show="showControls || !playing"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex flex-col justify-end"
      @click.stop
    >
      {{-- Progress Bar --}}
      <div class="px-6 pb-4">
        <div class="relative w-full h-1 bg-white/30 rounded-full cursor-pointer" @click="seekToPercent(($event.offsetX / $event.target.offsetWidth) * 100)">
          <div class="absolute top-0 left-0 h-full bg-white rounded-full transition-all duration-200" :style="`width: ${duration > 0 ? (currentTime / duration) * 100 : 0}%`"></div>
        </div>
      </div>

      {{-- Control Bar --}}
      <div class="flex items-center justify-between px-6 pb-6">
        <div class="flex items-center space-x-4">
          {{-- Play/Pause Button --}}
          <button @click="togglePlay()" class="text-white hover:text-gray-300 transition-colors duration-200">
            <svg x-show="!playing" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
              <path d="M8 5v14l11-7z"/>
            </svg>
            <svg x-show="playing" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" style="display: none;">
              <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>
          </button>

          {{-- Volume Button --}}
          <button @click="toggleMute()" class="text-white hover:text-gray-300 transition-colors duration-200">
            <svg x-show="!muted && volume > 0" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
            </svg>
            <svg x-show="muted || volume === 0" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" style="display: none;">
              <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
            </svg>
          </button>

          {{-- Time Display --}}
          <div class="text-white text-sm font-mono">
            <span x-text="formatTime(currentTime)"></span>
            <span class="text-gray-300"> / </span>
            <span x-text="formatTime(duration)"></span>
          </div>
        </div>

        {{-- Right Controls --}}
        <div class="flex items-center space-x-4">
          {{-- Fullscreen Button --}}
          <button @click="toggleFullscreen()" class="text-white hover:text-gray-300 transition-colors duration-200">
            <svg x-show="!fullscreen" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
            </svg>
            <svg x-show="fullscreen" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" style="display: none;">
              <path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  @endif

  {{-- Video Info Overlay (when paused) --}}
  @if($title || $description)
    <div 
      x-show="!playing"
      class="absolute inset-0 bg-black/40 flex items-center justify-center"
      @click.stop="togglePlay()"
    >
      <div class="text-center text-white p-6">
        @if($title)
          <x-heading
            :as="HeadingTag::H3"
            :size="HeadingSize::H4"
            class="text-white mb-2"
          >
            {{ $title }}
          </x-heading>
        @endif

        @if($description)
          <x-text
            :as="TextTag::P"
            :size="TextSize::BASE"
            class="text-gray-200"
          >
            {{ $description }}
          </x-text>
        @endif
      </div>
    </div>
  @endif
</div>