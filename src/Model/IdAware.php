<?php

declare(strict_types=1);

namespace Model;

interface IdAware
{
    public function getId(): string;
}