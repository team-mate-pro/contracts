<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\FeatureToggle;

interface FeatureToggleInterface
{
    public function getType(): string;
    public function getStatus(): FeatureToggleStatus;
}
