<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Event;

use Core23\LastFmBundle\Event\AuthFailedEvent;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class AuthFailedEventTest extends TestCase
{
    public function testCreation(): void
    {
        $event = new AuthFailedEvent();

        $this->assertInstanceOf(Event::class, $event);
    }

    public function testGetResponse(): void
    {
        $event = new AuthFailedEvent();

        $this->assertNull($event->getResponse());
    }

    public function testSetResponse(): void
    {
        /** @var ObjectProphecy&Response $reponse */
        $reponse = $this->prophesize(Response::class);

        $event = new AuthFailedEvent();
        $event->setResponse($reponse->reveal());

        $this->assertSame($reponse->reveal(), $event->getResponse());
    }
}
