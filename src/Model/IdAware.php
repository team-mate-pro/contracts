<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

interface IdAware
{
    public function getId(): string;
}
