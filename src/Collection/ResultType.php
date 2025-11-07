<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Collection;

enum ResultType
{
    case SUCCESS;
    case SUCCESS_NO_CONTENT;
    case FAILURE;
    case ACCEPTED;
    case DUPLICATED;
    case NOT_FOUND;
    case LOCKED;
    case GONE;
    case EXPIRED;
    case SUCCESS_CREATED;
}
