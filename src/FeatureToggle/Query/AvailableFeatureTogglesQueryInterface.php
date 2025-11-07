<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\FeatureToggle\Query;

use TeamMatePro\Contracts\FeatureToggle\FeatureToggleInterface;
use TeamMatePro\Contracts\FeatureToggle\QuotableFeatureToggleInterface;

interface AvailableFeatureTogglesQueryInterface
{
    /**
     * @return FeatureToggleInterface[]|QuotableFeatureToggleInterface[]
     */
    public function findAllFeatureToggles(): array;
}
