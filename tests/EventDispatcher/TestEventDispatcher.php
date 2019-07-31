<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\EventDispatcher;

use Core23\LastFmBundle\Event\AuthFailedEvent;
use Core23\LastFmBundle\Event\AuthSuccessEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class TestEventDispatcher implements EventDispatcherInterface
{
    private $response;

    public function dispatch($event)
    {
        if ($event instanceof AuthFailedEvent || $event instanceof AuthSuccessEvent) {
            $event->setResponse($this->response);
        }
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }
}
