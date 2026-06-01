<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\Meta\Field;

enum StringFormat: string
{
    case DateTime = 'date-time';
    case Uri = 'uri';
    case Email = 'email';
    case Ip = 'ip';
    case Uuid = 'uuid';
    case HexColor = 'hex-color';
}
