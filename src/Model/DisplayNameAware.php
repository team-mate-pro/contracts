<?php

declare(strict_types=1);

namespace Model;

/**
 * Returns display name that can be used within an app.
 * Must always be computed - for example there might be a user with e-mail and optional
 * first & last names - so displayName might be computed on the first part of an e-mail.
 */
interface DisplayNameAware
{
    public function getDisplayName(): string;
}