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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

final class AuthFailedEventTest extends TestCase
{
    public function testCreation(): void
    {
        $event = new AuthFailedEvent();

        static::assertInstanceOf(Event::class, $event);
    }

    public function testGetResponse(): void
    {
        $event = new AuthFailedEvent();

        static::assertNull($event->getResponse());
    }

    public function testSetResponse(): void
    {
        $reponse = $this->prophesize(Response::class);

        $event = new AuthFailedEvent();
        $event->setResponse($reponse->reveal());

        static::assertSame($reponse->reveal(), $event->getResponse());
    }
}
