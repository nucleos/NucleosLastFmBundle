<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Session;

use Core23\LastFm\Connection\Session as LastFmSession;
use Core23\LastFm\Connection\SessionInterface;
use Core23\LastFmBundle\Session\SessionManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionManagerTest extends TestCase
{
    public function testIsAuthenticated(): void
    {
        $session = $this->prophesize(Session::class);
        $session->get('_CORE23_LASTFM_TOKEN')
            ->willReturn(true)
        ;

        $manager = new SessionManager($session->reveal());
        $this->assertTrue($manager->isAuthenticated());
    }

    public function testIsNotAuthenticated(): void
    {
        $session = $this->prophesize(Session::class);
        $session->get('_CORE23_LASTFM_TOKEN')
            ->willReturn(false)
        ;

        $manager = new SessionManager($session->reveal());
        $this->assertFalse($manager->isAuthenticated());
    }

    public function testGetUsername(): void
    {
        $session = $this->prophesize(Session::class);
        $session->get('_CORE23_LASTFM_NAME')
            ->willReturn('MyUser')
        ;

        $manager = new SessionManager($session->reveal());
        $this->assertSame('MyUser', $manager->getUsername());
    }

    public function testGetUsernameNotExist(): void
    {
        $session = $this->prophesize(Session::class);
        $session->get('_CORE23_LASTFM_NAME')
            ->willReturn(null)
        ;

        $manager = new SessionManager($session->reveal());
        $this->assertNull($manager->getUsername());
    }

    public function testStore(): void
    {
        $lastfmSession = new LastFmSession('YourName', 'YourToken');

        $session = $this->prophesize(Session::class);
        $session->set('_CORE23_LASTFM_NAME', 'YourName')->shouldBeCalled();
        $session->set('_CORE23_LASTFM_TOKEN', 'YourToken')->shouldBeCalled();

        $manager = new SessionManager($session->reveal());
        $manager->store($lastfmSession);

        $this->assertTrue(true);
    }

    public function testClear(): void
    {
        $session = $this->prophesize(Session::class);
        $session->remove('_CORE23_LASTFM_TOKEN')->shouldBeCalled();
        $session->remove('_CORE23_LASTFM_NAME')->shouldBeCalled();

        $manager = new SessionManager($session->reveal());
        $manager->clear();
    }

    public function testGetSession(): void
    {
        $session = $this->prophesize(Session::class);
        $session->get('_CORE23_LASTFM_NAME')
            ->willReturn('MyUser')
        ;
        $session->get('_CORE23_LASTFM_TOKEN')
            ->willReturn('TheToken')
        ;

        $manager = new SessionManager($session->reveal());

        /** @var SessionInterface $lastfmSession */
        $lastfmSession = $manager->getSession();

        $this->assertNotNull($lastfmSession);
        $this->assertSame('MyUser', $lastfmSession->getName());
        $this->assertSame('TheToken', $lastfmSession->getKey());
    }
}
