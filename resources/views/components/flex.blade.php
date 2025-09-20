<?php
// Initialize values to prevent PHP warnings
$direction           = $direction ?? null;
$gapsize             = $gapsize ?? null;
$justify             = $justify ?? null;
$align               = $align ?? null;
$wrap                = $wrap ?? null;
$classes             = $classes ?? null;

// Convert enum values to strings
$directionClass = $direction?->value ?? '';
$gapsizeClass = $gapsize?->value ?? '';
$justifyClass = $justify?->value ?? '';
$alignClass = $align?->value ?? '';

$component_classes   = array_filter(['flex', $directionClass, $gapsizeClass, $justifyClass, $alignClass, $wrap, $classes]);
?>

<div {{ $attributes->merge([
	  'class' => implode(' ', $component_classes)
	]) }}
>
  {!! $slot !!}
</div>
