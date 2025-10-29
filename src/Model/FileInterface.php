<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

use DateTimeInterface;

interface FileInterface extends IdAware, NameAware
{
    public function getCreatedAt(): DateTimeInterface;

    public function getMime(): string;

    public function getBytes(): int;

    public function getRealPath(): string;

    public function isWebImage(): bool;

    /**
     * Public representation of the file, for example on AWS S3
     */
    public function getFileUrl(): ?string;
}
