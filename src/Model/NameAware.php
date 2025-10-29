<?php

declare(strict_types=1);

namespace Model;

interface NameAware
{
    public function getName(): ?string;
}