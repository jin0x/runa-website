<?php
// Initialize values to prevent PHP warnings
$columns             = $columns ?? null;
$gapsize             = $gapsize ?? null;
$rowgapsize          = $rowgapsize ?? null;
$colgapsize          = $colgapsize ?? null;
$classes             = $classes ?? null;
$component_classes   = [ $classes, $gapsize, $rowgapsize, $colgapsize, $columns ];
?>

<div {{ $attributes->merge([
	  'class' => implode(' ', $component_classes)
	]) }}
>
  {!! $slot !!}
</div>
