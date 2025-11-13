@php
  use App\Enums\ButtonVariant;
  use App\Enums\ButtonType;
  use App\Enums\ButtonSize;
@endphp

@props([
    'variant' => ButtonVariant::PRIMARY,
    'as' => ButtonType::LINK,
    'href'=> '',
    'icon' => false, // Boolean to show/hide icon
    'iconPosition' => 'none', // 'left', 'right', or 'none'
    'iconType' => 'arrow', // 'arrow' or 'user'
    'size' => ButtonSize::DEFAULT, // New size prop with DEFAULT as default
    'class' => '' // Additional custom classes
])

@php
  // Define variant classes for buttons
  $variantClasses = [
      ButtonVariant::PRIMARY => 'btn-primary text-primary-dark border-1 border-transparent hover:text-primary-light hover:border-primary-green-neon',
      ButtonVariant::SECONDARY => 'btn-secondary text-primary-light border-1 border-primary-green-neon transition-all duration-300 hover:text-primary-dark hover:border-transparent',
      ButtonVariant::LIGHT => 'btn-light text-primary-dark border-1 border-white transition-all duration-300 hover:bg-white hover:bg-opacity-100',
      ButtonVariant::DARK => 'btn-dark text-white border-1 border-transparent hover:text-primary-light hover:border-primary-green-neon',
      ButtonVariant::NAV => 'btn-nav transition-all duration-300',
  ];

  // Define background styles (using CSS custom properties for gradients)
  $backgroundStyles = [
      ButtonVariant::PRIMARY => 'background: var(--gradient-1);',
      ButtonVariant::SECONDARY => 'background: var(--gradient-3);',
      ButtonVariant::LIGHT => 'background: linear-gradient(90deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%);',
      ButtonVariant::DARK => 'background: var(--color-primary-dark);',
      ButtonVariant::NAV => 'background: var(--gradient-3);',
  ];

  // Define size classes for buttons
  $sizeClasses = [
      ButtonSize::DEFAULT => 'px-6 py-3 lg:min-h-[55px]',
      ButtonSize::SMALL => 'px-6 py-2',
      ButtonSize::LARGE => 'px-10 py-6',
  ];

  $textClasses = [
      ButtonSize::DEFAULT => 'text-small',
      ButtonSize::SMALL => 'text-small',
      ButtonSize::LARGE => 'heading-3',
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
  $buttonClass = trim("inline-flex items-center gap-2 rounded-full !no-underline {$variantClasses[$variant]} {$sizeClasses[$size]} {$class}");

  // Get the background style for the current variant
  $backgroundStyle = $backgroundStyles[$variant];

  // Get the text class for the current size - add gradient class for NAV variant
  $textClass = $textClasses[$size];
  if ($variant === ButtonVariant::NAV) {
      $textClass .= ' text-gradient-primary';
  }

  // Determine if we should show icon (backward compatibility)
  $showIcon = $icon || $iconPosition !== 'none';
  $actualIconPosition = $iconPosition !== 'none' ? $iconPosition : ($icon ? 'right' : 'none');
@endphp

@if ($as === ButtonType::BUTTON)
  <button {{ $attributes->merge(['class' => $buttonClass, 'type' => 'button', 'style' => $backgroundStyle]) }}>
    {{-- Left Icon --}}
    @if($actualIconPosition === 'left')
      <span class="flex items-center gap-3">
        {{-- User Icon --}}
        @if($iconType === 'user')
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="11.5" stroke="url(#userIconGradient)" stroke-opacity="0.5"/>
            <path d="M12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12Z" stroke="url(#userIconGradient)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M6 18C6 15.7909 8.68629 14 12 14C15.3137 14 18 15.7909 18 18" stroke="url(#userIconGradient)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <defs>
              <linearGradient id="userIconGradient" x1="12" y1="0" x2="12" y2="24" gradientUnits="userSpaceOnUse">
                <stop stop-color="#FFF"/>
                <stop offset="1" stop-color="#FFF" stop-opacity="0.5"/>
              </linearGradient>
            </defs>
          </svg>
        @endif

        {{-- Divider --}}
        <svg width="1" height="32" viewBox="0 0 1 32" fill="none" xmlns="http://www.w3.org/2000/svg">
          <line x1="0.5" y1="0" x2="0.5" y2="32" stroke="url(#dividerGradient)" stroke-width="1"/>
          <defs>
            <linearGradient id="dividerGradient" x1="0.5" y1="0" x2="0.5" y2="32" gradientUnits="userSpaceOnUse">
              <stop offset="0.1634" stop-color="white" stop-opacity="0.1"/>
              <stop offset="1" stop-color="white" stop-opacity="0.4"/>
            </linearGradient>
          </defs>
        </svg>
      </span>
    @endif

    <span class="{{ $textClass }}">
      {{ $slot }}
    </span>

    {{-- Right Icon --}}
    @if($actualIconPosition === 'right' && $iconType === 'arrow')
      <span class="flex-shrink-0">
        <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 16.8335C0 7.99694 7.16344 0.833496 16 0.833496C24.8366 0.833496 32 7.99694 32 16.8335C32 25.6701 24.8366 32.8335 16 32.8335C7.16344 32.8335 0 25.6701 0 16.8335Z" fill="#201F2C"/>
          <path d="M20.1419 20.5271C19.8789 20.7463 19.6232 20.8558 19.3748 20.8558C19.141 20.8412 18.9438 20.7463 18.783 20.5709C18.6369 20.381 18.5639 20.1545 18.5639 19.8915V17.8752H9.20542C8.82553 17.8752 8.526 17.7875 8.30683 17.6122C8.10228 17.4222 8 17.1592 8 16.8232C8 16.5017 8.10228 16.246 8.30683 16.0561C8.526 15.8661 8.82553 15.7712 9.20542 15.7712H18.5639V13.7548C18.5639 13.4918 18.6369 13.2727 18.783 13.0973C18.9438 12.9074 19.141 12.8124 19.3748 12.8124C19.6232 12.7978 19.8789 12.9001 20.1419 13.1192L23.4294 15.815C23.8093 16.1364 23.9992 16.4725 23.9992 16.8232C24.0138 17.1738 23.8239 17.5099 23.4294 17.8313L20.1419 20.5271Z" fill="#F5F5F5"/>
        </svg>
      </span>
    @endif
  </button>
@else
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClass, 'style' => $backgroundStyle]) }}>
    {{-- Left Icon --}}
    @if($actualIconPosition === 'left')
      <span class="flex items-center gap-3">
        {{-- User Icon --}}
        @if($iconType === 'user')
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="11.5" stroke="url(#userIconGradient)" stroke-opacity="0.5"/>
            <path d="M12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12Z" stroke="url(#userIconGradient)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M6 18C6 15.7909 8.68629 14 12 14C15.3137 14 18 15.7909 18 18" stroke="url(#userIconGradient)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <defs>
              <linearGradient id="userIconGradient" x1="12" y1="0" x2="12" y2="24" gradientUnits="userSpaceOnUse">
                <stop stop-color="#FFF"/>
                <stop offset="1" stop-color="#FFF" stop-opacity="0.5"/>
              </linearGradient>
            </defs>
          </svg>
        @endif

        {{-- Divider --}}
        <svg width="1" height="32" viewBox="0 0 1 32" fill="none" xmlns="http://www.w3.org/2000/svg">
          <line x1="0.5" y1="0" x2="0.5" y2="32" stroke="url(#dividerGradient)" stroke-width="1"/>
          <defs>
            <linearGradient id="dividerGradient" x1="0.5" y1="0" x2="0.5" y2="32" gradientUnits="userSpaceOnUse">
              <stop offset="0.1634" stop-color="white" stop-opacity="0.1"/>
              <stop offset="1" stop-color="white" stop-opacity="0.4"/>
            </linearGradient>
          </defs>
        </svg>
      </span>
    @endif

    <span class="{{ $textClass }}">{{ $slot }}</span>

    {{-- Right Icon --}}
    @if($actualIconPosition === 'right' && $iconType === 'arrow')
      <span class="flex-shrink-0">
        <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 16.8335C0 7.99694 7.16344 0.833496 16 0.833496C24.8366 0.833496 32 7.99694 32 16.8335C32 25.6701 24.8366 32.8335 16 32.8335C7.16344 32.8335 0 25.6701 0 16.8335Z" fill="#201F2C"/>
          <path d="M20.1419 20.5271C19.8789 20.7463 19.6232 20.8558 19.3748 20.8558C19.141 20.8412 18.9438 20.7463 18.783 20.5709C18.6369 20.381 18.5639 20.1545 18.5639 19.8915V17.8752H9.20542C8.82553 17.8752 8.526 17.7875 8.30683 17.6122C8.10228 17.4222 8 17.1592 8 16.8232C8 16.5017 8.10228 16.246 8.30683 16.0561C8.526 15.8661 8.82553 15.7712 9.20542 15.7712H18.5639V13.7548C18.5639 13.4918 18.6369 13.2727 18.783 13.0973C18.9438 12.9074 19.141 12.8124 19.3748 12.8124C19.6232 12.7978 19.8789 12.9001 20.1419 13.1192L23.4294 15.815C23.8093 16.1364 23.9992 16.4725 23.9992 16.8232C24.0138 17.1738 23.8239 17.5099 23.4294 17.8313L20.1419 20.5271Z" fill="#F5F5F5"/>
        </svg>
      </span>
    @endif
  </a>
@endif