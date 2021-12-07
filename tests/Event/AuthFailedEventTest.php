<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\Event;

use Nucleos\LastFmBundle\Event\AuthFailedEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class AuthFailedEventTest extends TestCase
{
    public function testGetResponse(): void
    {
        $event = new AuthFailedEvent();

        static::assertNull($event->getResponse());
    }

    public function testSetResponse(): void
    {
        $reponse = new Response();

        $event = new AuthFailedEvent();
        $event->setResponse($reponse);

        static::assertSame($reponse, $event->getResponse());
    }
}
