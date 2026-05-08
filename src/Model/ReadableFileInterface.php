<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

interface ReadableFileInterface extends FileInterface
{
    public function getContent(): string;
}
