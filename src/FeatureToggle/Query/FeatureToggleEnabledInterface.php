<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\FeatureToggle\Query;

/**
 * Returns true if feature is enabled and available to use (explicit enabled status)
 */
interface FeatureToggleEnabledInterface
{
    public function isFeatureEnabled(string $feature): bool;
}
