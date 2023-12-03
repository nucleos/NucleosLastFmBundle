<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\Session;

use Nucleos\LastFm\Session\Session as LastFmSession;
use Nucleos\LastFmBundle\Session\SessionManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

final class SessionManagerTest extends TestCase
{
    private Session $session;

    private RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());

        $request = new Request();
        $request->setSession($this->session);

        $this->requestStack = new RequestStack();
        $this->requestStack->push($request);
    }

    public function testIsAuthenticated(): void
    {
        $this->session->set('LASTFM_TOKEN', 'TheToken');

        $manager = new SessionManager($this->requestStack);
        self::assertTrue($manager->isAuthenticated());
    }

    public function testIsNotAuthenticated(): void
    {
        $manager = new SessionManager($this->requestStack);
        self::assertFalse($manager->isAuthenticated());
    }

    public function testGetUsername(): void
    {
        $this->session->set('LASTFM_NAME', 'MyUser');

        $manager = new SessionManager($this->requestStack);
        self::assertSame('MyUser', $manager->getUsername());
    }

    public function testGetUsernameNotExist(): void
    {
        $manager = new SessionManager($this->requestStack);
        self::assertNull($manager->getUsername());
    }

    public function testStore(): void
    {
        $lastfmSession = new LastFmSession('YourName', 'YourToken');

        $manager = new SessionManager($this->requestStack);
        $manager->store($lastfmSession);

        self::assertTrue($this->session->has('LASTFM_TOKEN'));
        self::assertTrue($this->session->has('LASTFM_NAME'));
    }

    public function testClear(): void
    {
        $this->session->set('LASTFM_TOKEN', 'TheToken');
        $this->session->set('LASTFM_NAME', 'MyUser');

        $manager = new SessionManager($this->requestStack);
        $manager->clear();

        self::assertFalse($this->session->has('LASTFM_TOKEN'));
        self::assertFalse($this->session->has('LASTFM_NAME'));
    }

    public function testGetSession(): void
    {
        $this->session->set('LASTFM_TOKEN', 'TheToken');
        $this->session->set('LASTFM_NAME', 'MyUser');

        $manager = new SessionManager($this->requestStack);

        $lastfmSession = $manager->getSession();

        self::assertNotNull($lastfmSession);
        self::assertSame('MyUser', $lastfmSession->getName());
        self::assertSame('TheToken', $lastfmSession->getKey());
    }

    public function testGetSessionWithNoAuth(): void
    {
        $manager = new SessionManager($this->requestStack);

        self::assertNull($manager->getSession());
    }
}
