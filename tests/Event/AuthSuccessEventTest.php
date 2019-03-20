<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Event;

use Core23\LastFm\Connection\SessionInterface;
use Core23\LastFmBundle\Event\AuthSuccessEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class AuthSuccessEventTest extends TestCase
{
    public function testCreation(): void
    {
        /** @var ObjectProphecy&SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);

        $event = new AuthSuccessEvent($session->reveal());

        $this->assertInstanceOf(Event::class, $event);
    }

    public function testGetUsername(): void
    {
        /** @var ObjectProphecy&SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->getName()->willReturn('MyUser');

        $event = new AuthSuccessEvent($session->reveal());

        $this->assertSame('MyUser', $event->getUsername());
    }

    public function testGetSession(): void
    {
        /** @var ObjectProphecy&SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);

        $event = new AuthSuccessEvent($session->reveal());

        $this->assertSame($session->reveal(), $event->getSession());
    }

    public function testGetResponse(): void
    {
        /** @var ObjectProphecy&SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);

        $event = new AuthSuccessEvent($session->reveal());

        $this->assertNull($event->getResponse());
    }

    public function testSetResponse(): void
    {
        /** @var ObjectProphecy&SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);

        /** @var MockObject&Response $session */
        $reponse = $this->prophesize(Response::class);

        $event = new AuthSuccessEvent($session->reveal());
        $event->setResponse($reponse->reveal());

        $this->assertSame($reponse->reveal(), $event->getResponse());
    }
}
