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
use Symfony\Component\HttpFoundation\Session\Session;

final class SessionManagerTest extends TestCase
{
    public function testIsAuthenticated(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->with('LASTFM_TOKEN')
            ->willReturn(true)
        ;

        $manager = new SessionManager($session);
        static::assertTrue($manager->isAuthenticated());
    }

    public function testIsNotAuthenticated(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->with('LASTFM_TOKEN')
            ->willReturn(false)
        ;

        $manager = new SessionManager($session);
        static::assertFalse($manager->isAuthenticated());
    }

    public function testGetUsername(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->with('LASTFM_NAME')
            ->willReturn('MyUser')
        ;

        $manager = new SessionManager($session);
        static::assertSame('MyUser', $manager->getUsername());
    }

    public function testGetUsernameNotExist(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->with('LASTFM_NAME')
            ->willReturn(null)
        ;

        $manager = new SessionManager($session);
        static::assertNull($manager->getUsername());
    }

    public function testStore(): void
    {
        $lastfmSession = new LastFmSession('YourName', 'YourToken');

        $session = $this->createMock(Session::class);
        $session->expects(static::exactly(2))->method('set')
            ->withConsecutive(
                ['LASTFM_NAME', 'YourName'],
                ['LASTFM_TOKEN', 'YourToken'],
            )
        ;

        $manager = new SessionManager($session);
        $manager->store($lastfmSession);
    }

    public function testClear(): void
    {
        $session = $this->createMock(Session::class);
        $session->expects(static::exactly(2))->method('remove')
            ->withConsecutive(
                ['LASTFM_NAME'],
                ['LASTFM_TOKEN'],
            )
        ;

        $manager = new SessionManager($session);
        $manager->clear();
    }

    public function testGetSession(): void
    {
        $session = $this->createMock(Session::class);
        $session->expects(static::exactly(3))->method('get')
            ->withConsecutive(
                ['LASTFM_TOKEN'],
                ['LASTFM_NAME'],
                ['LASTFM_TOKEN'],
            )
            ->willReturn(
                'TheToken',
                'MyUser',
                'TheToken'
            )
        ;

        $manager = new SessionManager($session);

        $lastfmSession = $manager->getSession();

        static::assertNotNull($lastfmSession);
        static::assertSame('MyUser', $lastfmSession->getName());
        static::assertSame('TheToken', $lastfmSession->getKey());
    }

    public function testGetSessionWithNoAuth(): void
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->with('LASTFM_TOKEN')
            ->willReturn(null)
        ;

        $manager = new SessionManager($session);

        static::assertNull($manager->getSession());
    }
}
