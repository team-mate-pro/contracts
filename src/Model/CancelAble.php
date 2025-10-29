<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

interface CancelAble
{
    public function isCanceled(): bool;

    public function isCancelAble(): bool;

    public function cancel(): void;
}
