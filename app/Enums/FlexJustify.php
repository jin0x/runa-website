<?php

namespace App\Enums;

enum FlexJustify: string
{
    case START = 'justify-start';
    case END = 'justify-end';
    case CENTER = 'justify-center';
    case BETWEEN = 'justify-between';
    case AROUND = 'justify-around';
    case EVENLY = 'justify-evenly';
}