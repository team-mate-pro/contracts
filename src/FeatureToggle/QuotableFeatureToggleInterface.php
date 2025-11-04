<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\FeatureToggle;

interface QuotableFeatureToggleInterface
{
    public function getType(): string;

    /**
     * If quota limit = null means there is no limit
     *
     * @return int|null
     */
    public function getQuotaLimit(): ?int;

    /**
     * @return int - current usage, should return 0 when not computed
     */
    public function getQuota(): int;

    public function getStatus(): FeatureToggleStatus;
}
