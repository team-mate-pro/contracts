<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

interface NameAware
{
    public function getName(): ?string;
}
