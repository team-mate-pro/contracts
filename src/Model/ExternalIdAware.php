<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

interface ExternalIdAware
{
    /**
     * Should return external object id if related.
     */
    public function getExternalId(): ?string;
}
