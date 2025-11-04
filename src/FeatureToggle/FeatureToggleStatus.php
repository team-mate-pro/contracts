<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\FeatureToggle;

/**
 * Enabled/Disabled - means it's possible to toggle this feature in the current scope.
 * Unpaid - subscription plan has not been paid.
 * QuoteReached - means the quota limit has been reached
 * Unavailable - feature is not available for the selected organization type.
 */
enum FeatureToggleStatus: string
{
    case Enabled = 'enabled';
    case Disabled = 'disabled';
    case Unpaid = 'unpaid';
    case Unavailable = 'unavailable';
    case QuoteReached = 'quotaReached';
}
