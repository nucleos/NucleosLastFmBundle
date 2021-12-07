<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\Event;

use Nucleos\LastFm\Session\SessionInterface;
use Nucleos\LastFmBundle\Event\AuthSuccessEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class AuthSuccessEventTest extends TestCase
{
    public function testGetUsername(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $session->method('getName')->willReturn('MyUser');

        $event = new AuthSuccessEvent($session);

        static::assertSame('MyUser', $event->getUsername());
    }

    public function testGetSession(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $event = new AuthSuccessEvent($session);

        static::assertSame($session, $event->getSession());
    }

    public function testGetResponse(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $event = new AuthSuccessEvent($session);

        static::assertNull($event->getResponse());
    }

    public function testSetResponse(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $reponse = new Response();

        $event = new AuthSuccessEvent($session);
        $event->setResponse($reponse);

        static::assertSame($reponse, $event->getResponse());
    }
}
