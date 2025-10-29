<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

/**
 * Defines if some conditions to lock item have been achieved.
 */
interface IsLockAble
{
    public function isLocked(): bool;

    public function lock(): void;
}
