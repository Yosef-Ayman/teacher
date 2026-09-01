<?php

namespace App\Enums;

enum VideoProvider: string
{
    case Youtube = 'youtube';
    case VIMEO = 'vimeo';
    case STREAMABLE = 'streamable';
}
