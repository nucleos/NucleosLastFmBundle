<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Session;

use Core23\LastFm\Session\SessionInterface;

interface SessionManagerInterface
{
    /**
     * Returns the auth status.
     */
    public function isAuthenticated(): bool;

    /**
     * Get the session username.
     */
    public function getUsername(): ?string;

    public function store(SessionInterface $lastFmSession): void;

    /**
     * Removes all stored sessions.
     */
    public function clear(): void;

    public function getSession(): ?SessionInterface;
}
