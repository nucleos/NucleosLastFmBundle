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
use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

final class SessionManager implements SessionManagerInterface
{
    private const SESSION_LASTFM_NAME  = 'LASTFM_NAME';

    private const SESSION_LASTFM_TOKEN = 'LASTFM_TOKEN';

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function isAuthenticated(): bool
    {
        return $this->getHttpSession()->has(self::SESSION_LASTFM_TOKEN);
    }

    public function getUsername(): ?string
    {
        return $this->getHttpSession()->get(self::SESSION_LASTFM_NAME);
    }

    public function store(SessionInterface $lastFmSession): void
    {
        $session = $this->getHttpSession();

        $session->set(self::SESSION_LASTFM_NAME, $lastFmSession->getName());
        $session->set(self::SESSION_LASTFM_TOKEN, $lastFmSession->getKey());
    }

    public function clear(): void
    {
        $session = $this->getHttpSession();

        $session->remove(self::SESSION_LASTFM_NAME);
        $session->remove(self::SESSION_LASTFM_TOKEN);
    }

    public function getSession(): ?SessionInterface
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $session = $this->getHttpSession();

        return new LastFmSession(
            $session->get(self::SESSION_LASTFM_NAME),
            $session->get(self::SESSION_LASTFM_TOKEN)
        );
    }

    private function getHttpSession(): Session
    {
        $request = $this->requestStack->getMainRequest();

        if (null === $request) {
            throw new RuntimeException('Could not retrieve request.');
        }

        $session = $request->hasSession() ? $request->getSession() : null;

        if (!$session instanceof Session) {
            throw new RuntimeException('Could not retrieve session from request.');
        }

        return $session;
    }
}
