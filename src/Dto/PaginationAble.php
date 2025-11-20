<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Dto;

use TeamMatePro\Contracts\Collection\Pagination;

interface PaginationAble
{
    public function getPagination(): Pagination;
}