<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Policy;

use Exception;

/**
 * Exception thrown when a policy intention is not satisfied.
 *
 * @template TCause of \BackedEnum
 */
class PolicyException extends Exception
{
    /**
     * @param TCause $cause
     */
    public function __construct(
        public readonly \BackedEnum $cause,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return TCause
     */
    public function getCause(): \BackedEnum
    {
        return $this->cause;
    }
}
