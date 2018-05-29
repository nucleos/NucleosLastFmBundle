<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

final class AuthFailedEvent extends Event
{
    /**
     * @var Response|null
     */
    private $response;

    /**
     * @return null|Response
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param null|Response $response
     */
    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }
}
