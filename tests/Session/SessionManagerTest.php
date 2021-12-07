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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

final class SessionManagerTest extends TestCase
{
    /**
     * @var Session&MockObject
     */
    private Session $session;

    private RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->session = $this->createMock(Session::class);

        $request = new Request();
        $request->setSession($this->session);

        $this->requestStack = new RequestStack();
        $this->requestStack->push($request);
    }

    public function testIsAuthenticated(): void
    {
        $this->session->method('get')->with('LASTFM_TOKEN')
            ->willReturn(true)
        ;

        $manager = new SessionManager($this->requestStack);
        static::assertTrue($manager->isAuthenticated());
    }

    public function testIsNotAuthenticated(): void
    {
        $this->session->method('get')->with('LASTFM_TOKEN')
            ->willReturn(false)
        ;

        $manager = new SessionManager($this->requestStack);
        static::assertFalse($manager->isAuthenticated());
    }

    public function testGetUsername(): void
    {
        $this->session->method('get')->with('LASTFM_NAME')
            ->willReturn('MyUser')
        ;

        $manager = new SessionManager($this->requestStack);
        static::assertSame('MyUser', $manager->getUsername());
    }

    public function testGetUsernameNotExist(): void
    {
        $this->session = $this->createMock(Session::class);
        $this->session->method('get')->with('LASTFM_NAME')
            ->willReturn(null)
        ;

        $manager = new SessionManager($this->requestStack);
        static::assertNull($manager->getUsername());
    }

    public function testStore(): void
    {
        $lastfmSession = new LastFmSession('YourName', 'YourToken');

        $this->session->expects(static::exactly(2))->method('set')
            ->withConsecutive(
                ['LASTFM_NAME', 'YourName'],
                ['LASTFM_TOKEN', 'YourToken'],
            )
        ;

        $manager = new SessionManager($this->requestStack);
        $manager->store($lastfmSession);
    }

    public function testClear(): void
    {
        $this->session->expects(static::exactly(2))->method('remove')
            ->withConsecutive(
                ['LASTFM_NAME'],
                ['LASTFM_TOKEN'],
            )
        ;

        $manager = new SessionManager($this->requestStack);
        $manager->clear();
    }

    public function testGetSession(): void
    {
        $this->session->expects(static::exactly(3))->method('get')
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

        $manager = new SessionManager($this->requestStack);

        $lastfmSession = $manager->getSession();

        static::assertNotNull($lastfmSession);
        static::assertSame('MyUser', $lastfmSession->getName());
        static::assertSame('TheToken', $lastfmSession->getKey());
    }

    public function testGetSessionWithNoAuth(): void
    {
        $this->session->method('get')->with('LASTFM_TOKEN')
            ->willReturn(null)
        ;

        $manager = new SessionManager($this->requestStack);

        static::assertNull($manager->getSession());
    }
}
