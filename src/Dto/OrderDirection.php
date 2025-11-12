<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Dto;

enum OrderDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
