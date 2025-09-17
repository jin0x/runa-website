@php
  use App\Enums\ButtonVariant;
  use App\Enums\ButtonType;
  use App\Enums\ButtonSize;
@endphp

@props([
    'variant' => ButtonVariant::GREEN,
    'as' => ButtonType::LINK,
    'href'=> '',
    'icon' => false, // Boolean to show/hide icon
    'size' => ButtonSize::DEFAULT, // New size prop with DEFAULT as default
    'class' => '' // Additional custom classes
])

@php
  // Define variant classes for buttons
  $variantClasses = [
      ButtonVariant::GREEN => 'bg-primary-lime text-gray-900 hover:bg-primary-light',
      ButtonVariant::PURPLE => 'bg-primary-violet text-white hover:bg-primary-light hover:text-gray-900',
      ButtonVariant::LIGHT => 'bg-white text-gray-900 hover:bg-primary-dark hover:text-white',
      ButtonVariant::DARK => 'bg-primary-dark text-white hover:bg-white hover:text-gray-900',
      ButtonVariant::TRANSPARENT => 'bg-transparent text-navy hover:bg-gray-100',
  ];

  // Define size classes for buttons
  $sizeClasses = [
      ButtonSize::DEFAULT => 'px-6 py-3',
      ButtonSize::SMALL => 'px-6 py-2',
  ];

  // Validate the provided "variant" prop
  if (!in_array($variant, ButtonVariant::getValues(), true)) {
      throw new InvalidArgumentException("Invalid button variant: {$variant}");
  }

  // Validate the provided "size" prop
  if (!in_array($size, ButtonSize::getValues(), true)) {
      throw new InvalidArgumentException("Invalid button size: {$size}");
  }

  // Combine the base button class with the variant class, size class and any additional classes
  $buttonClass = trim("inline-flex items-center gap-2 rounded-full font-normal font-sans {$variantClasses[$variant]} {$sizeClasses[$size]} {$class}");
@endphp

@if ($as === ButtonType::BUTTON)
  <button {{ $attributes->merge(['class' => $buttonClass, 'type' => 'button']) }}>
    {{ $slot }}
  </button>
@else
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClass]) }}>
    <span>{{ $slot }}</span>

    @if ($icon)
      <span class="flex-shrink-0">
        <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 16.8335C0 7.99694 7.16344 0.833496 16 0.833496C24.8366 0.833496 32 7.99694 32 16.8335C32 25.6701 24.8366 32.8335 16 32.8335C7.16344 32.8335 0 25.6701 0 16.8335Z" fill="#201F2C"/>
          <path d="M20.1419 20.5271C19.8789 20.7463 19.6232 20.8558 19.3748 20.8558C19.141 20.8412 18.9438 20.7463 18.783 20.5709C18.6369 20.381 18.5639 20.1545 18.5639 19.8915V17.8752H9.20542C8.82553 17.8752 8.526 17.7875 8.30683 17.6122C8.10228 17.4222 8 17.1592 8 16.8232C8 16.5017 8.10228 16.246 8.30683 16.0561C8.526 15.8661 8.82553 15.7712 9.20542 15.7712H18.5639V13.7548C18.5639 13.4918 18.6369 13.2727 18.783 13.0973C18.9438 12.9074 19.141 12.8124 19.3748 12.8124C19.6232 12.7978 19.8789 12.9001 20.1419 13.1192L23.4294 15.815C23.8093 16.1364 23.9992 16.4725 23.9992 16.8232C24.0138 17.1738 23.8239 17.5099 23.4294 17.8313L20.1419 20.5271Z" fill="#F5F5F5"/>
        </svg>
      </span>
    @endif
  </a>
@endif
