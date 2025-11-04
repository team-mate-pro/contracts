<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use InvalidArgumentException;
use Stringable;

/**
 * Immutable email value object with validation.
 */
readonly class Email implements Stringable
{
    private string $email;

    public function __construct(string $email)
    {
        $trimmedEmail = trim($email);

        if ($trimmedEmail === '') {
            throw new InvalidArgumentException('Email address cannot be empty');
        }

        if (!filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                sprintf('Invalid email address format: %s', $email)
            );
        }

        $this->email = strtolower($trimmedEmail);
    }

    /**
     * Create Email from string (alias for constructor)
     */
    public static function fromString(string $email): self
    {
        return new self($email);
    }

    /**
     * Get the email address as a string
     */
    public function toString(): string
    {
        return $this->email;
    }

    /**
     * Get the email address
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the local part (before @) of the email address
     */
    public function getLocalPart(): string
    {
        $parts = explode('@', $this->email);

        return $parts[0];
    }

    /**
     * Get the domain part (after @) of the email address
     */
    public function getDomain(): string
    {
        $parts = explode('@', $this->email);

        return $parts[1] ?? '';
    }

    /**
     * Check if email belongs to a specific domain
     */
    public function hasDomain(string $domain): bool
    {
        return strtolower($this->getDomain()) === strtolower($domain);
    }

    /**
     * Check if two email addresses are equal
     */
    public function equals(self $other): bool
    {
        return $this->email === $other->email;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
