<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Action;

use Nucleos\LastFmBundle\Event\AuthFailedEvent;
use Nucleos\LastFmBundle\NucleosLastFmEvents;
use Nucleos\LastFmBundle\Session\SessionManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class AuthErrorAction
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        Environment $twig,
        RouterInterface $router,
        SessionManagerInterface $sessionManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->twig            = $twig;
        $this->router          = $router;
        $this->sessionManager  = $sessionManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): Response
    {
        if ($this->sessionManager->isAuthenticated()) {
            return new RedirectResponse($this->router->generate('nucleos_lastfm_success'));
        }

        $this->sessionManager->clear();

        $event = new AuthFailedEvent();
        $this->eventDispatcher->dispatch($event, NucleosLastFmEvents::AUTH_ERROR);

        if (null !== $response = $event->getResponse()) {
            return $response;
        }

        return new Response($this->twig->render('@NucleosLastFm/Auth/error.html.twig'), Response::HTTP_UNAUTHORIZED);
    }
}
