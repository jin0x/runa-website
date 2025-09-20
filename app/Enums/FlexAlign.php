<?php

namespace App\Enums;

enum FlexAlign: string
{
    case START = 'items-start';
    case END = 'items-end';
    case CENTER = 'items-center';
    case BASELINE = 'items-baseline';
    case STRETCH = 'items-stretch';
}