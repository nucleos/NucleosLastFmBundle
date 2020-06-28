<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Session;

use Nucleos\LastFm\Session\Session as LastFmSession;
use Nucleos\LastFm\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class SessionManager implements SessionManagerInterface
{
    private const SESSION_LASTFM_NAME  = 'LASTFM_NAME';

    private const SESSION_LASTFM_TOKEN = 'LASTFM_TOKEN';

    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isAuthenticated(): bool
    {
        return (bool) $this->session->get(static::SESSION_LASTFM_TOKEN);
    }

    public function getUsername(): ?string
    {
        return $this->session->get(static::SESSION_LASTFM_NAME);
    }

    public function store(SessionInterface $lastFmSession): void
    {
        $this->session->set(static::SESSION_LASTFM_NAME, $lastFmSession->getName());
        $this->session->set(static::SESSION_LASTFM_TOKEN, $lastFmSession->getKey());
    }

    public function clear(): void
    {
        $this->session->remove(static::SESSION_LASTFM_NAME);
        $this->session->remove(static::SESSION_LASTFM_TOKEN);
    }

    public function getSession(): ?SessionInterface
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return new LastFmSession(
            $this->session->get(static::SESSION_LASTFM_NAME),
            $this->session->get(static::SESSION_LASTFM_TOKEN)
        );
    }
}
