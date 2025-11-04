<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\FeatureToggle;

use Symfony\Component\Serializer\Attribute\Groups;

#[Groups([FeatureToggleInterface::class])]
final readonly class FeatureToggle implements FeatureToggleInterface
{
    public function __construct(
        private string $type,
        private FeatureToggleStatus $status = FeatureToggleStatus::Enabled,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): FeatureToggleStatus
    {
        return $this->status;
    }
}
