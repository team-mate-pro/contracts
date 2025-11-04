<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use InvalidArgumentException;

/**
 * Immutable locale value object representing a language and optional country/region.
 * Format: language code (ISO 639-1) with optional country code (ISO 3166-1 alpha-2).
 * Examples: "en", "en_US", "pl_PL", "de_DE"
 */
readonly class Locale
{
    /**
     * @param string $languageCode ISO 639-1 two-letter language code (e.g., "en", "pl", "de")
     * @param Country|null $country Optional country/region
     */
    public function __construct(
        public string $languageCode,
        public ?Country $country = null,
    ) {
        if (!preg_match('/^[a-z]{2}$/', $this->languageCode)) {
            throw new InvalidArgumentException(
                sprintf('Language code must be a two-letter ISO 639-1 code, got: %s', $this->languageCode)
            );
        }
    }

    /**
     * Create Locale from string format (e.g., "en_US", "pl_PL", "en")
     */
    public static function fromString(string $locale): self
    {
        $parts = explode('_', $locale);

        if (count($parts) > 2) {
            throw new InvalidArgumentException(
                sprintf('Invalid locale format: %s. Expected format: "en" or "en_US"', $locale)
            );
        }

        $languageCode = strtolower($parts[0]);
        $country = null;

        if (isset($parts[1])) {
            $countryCode = strtoupper($parts[1]);
            $country = Country::tryFrom($countryCode);

            if ($country === null) {
                throw new InvalidArgumentException(
                    sprintf('Invalid country code: %s', $countryCode)
                );
            }
        }

        return new self($languageCode, $country);
    }

    /**
     * Get locale as string (e.g., "en_US", "pl_PL", "en")
     */
    public function toString(): string
    {
        if ($this->country === null) {
            return $this->languageCode;
        }

        return sprintf('%s_%s', $this->languageCode, $this->country->value);
    }

    /**
     * Get locale as string using underscore format (e.g., "en_US")
     */
    public function toUnderscoreFormat(): string
    {
        return $this->toString();
    }

    /**
     * Get locale as string using dash format (e.g., "en-US")
     */
    public function toDashFormat(): string
    {
        if ($this->country === null) {
            return $this->languageCode;
        }

        return sprintf('%s-%s', $this->languageCode, $this->country->value);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
