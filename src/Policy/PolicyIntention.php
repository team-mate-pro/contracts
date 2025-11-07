<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Policy;

enum PolicyIntention: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case REMOVE = 'remove';
}
