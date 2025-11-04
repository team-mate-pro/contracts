<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\FeatureToggle;

use Symfony\Component\Serializer\Attribute\Groups;

#[Groups([QuotableFeatureToggleInterface::class, FeatureToggleInterface::class])]
final readonly class QuotableFeatureToggle implements QuotableFeatureToggleInterface, FeatureToggleInterface
{
    public function __construct(
        private string $type,
        private ?int $quotaLimit = null,
        private int $quota = 0,
        private FeatureToggleStatus $status = FeatureToggleStatus::Enabled,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuotaLimit(): ?int
    {
        return $this->quotaLimit;
    }

    public function getStatus(): FeatureToggleStatus
    {
        return $this->status;
    }

    public function getQuota(): int
    {
        return $this->quota;
    }
}
