<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Dto;

use Stringable;

/**
 * Marker class to recognize that a value is undefined.
 */
final class Undefined implements Stringable
{
    public function __toString(): string
    {
        return 'N/A';
    }
}
