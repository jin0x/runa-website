<?php

namespace App\Enums;

enum FlexDirection: string
{
    case ROW = 'flex-row';
    case ROW_REVERSE = 'flex-row-reverse';
    case COLUMN = 'flex-col';
    case COLUMN_REVERSE = 'flex-col-reverse';
}