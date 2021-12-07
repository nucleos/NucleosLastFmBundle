<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Event;

use Nucleos\LastFm\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

final class AuthSuccessEvent extends Event
{
    private SessionInterface $session;

    private ?Response $response = null;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }

    public function getUsername(): string
    {
        return $this->session->getName();
    }
}
