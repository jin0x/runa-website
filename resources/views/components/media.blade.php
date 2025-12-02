@php
  /**
   * Media Component
   */
@endphp

@props([
    'mediaType' => 'image',
    'mediaUrl' => '',
    'altText' => '',
    'classes' => '',
    'containerClasses' => 'overflow-hidden',
])

<div class="{{ $containerClasses }}">
  @if($mediaType === 'image' && $mediaUrl)
    <img
      src="{{ $mediaUrl }}"
      alt="{{ $altText }}"
      class="{{ $classes }}"
    />
  @elseif($mediaType === 'video' && $mediaUrl)
    <video
      class="{{ $classes }}"
      controls
      playsinline
      preload="metadata"
    >
      <source src="{{ $mediaUrl }}" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  @elseif($mediaType === 'lottie' && $mediaUrl)
    <div
      class="{{ $classes }} lottie-animation"
      data-lottie-src="{{ $mediaUrl }}"
    ></div>
  @else
    <div class="bg-gray-200 {{ $classes }} flex items-center justify-center">
      <span class="text-gray-400">Media placeholder</span>
    </div>
  @endif
</div>