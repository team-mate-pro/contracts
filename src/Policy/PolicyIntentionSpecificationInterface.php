<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Policy;

use Throwable;

/**
 * An abstraction allowing to control that specific action is allowed ot not with conditional error thrown.
 *
 * @template T of object
 */
interface PolicyIntentionSpecificationInterface
{
    /**
     * @param T $object
     *
     * @throws Throwable
     */
    public function isSatisfiedBy(PolicyIntention $intention, object $object, bool $throw = false): bool;
}
