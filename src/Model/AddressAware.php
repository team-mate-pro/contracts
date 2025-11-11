<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

use TeamMatePro\Contracts\ValueObject\AddressInterface;

/**
 * Interface for entities that have an address property.
 * This allows generic handling of address-related operations like geocoding.
 */
interface AddressAware
{
    /**
     * Get the address associated with this entity.
     *
     * @return AddressInterface|null The address or null if not set
     */
    public function getAddress(): ?AddressInterface;

    public function setAddress(AddressInterface $address): void;
}
