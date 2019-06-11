<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Action;

use Core23\LastFmBundle\Core23LastFmEvents;
use Core23\LastFmBundle\Event\AuthFailedEvent;
use Core23\LastFmBundle\Session\SessionManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    /**
     * @param Environment              $twig
     * @param RouterInterface          $router
     * @param SessionManagerInterface  $sessionManager
     * @param EventDispatcherInterface $eventDispatcher
     */
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
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        if ($this->sessionManager->isAuthenticated()) {
            return new RedirectResponse($this->generateUrl('core23_lastfm_success'));
        }

        $this->sessionManager->clear();

        $event = new AuthFailedEvent();
        $this->eventDispatcher->dispatch($event, Core23LastFmEvents::AUTH_ERROR);

        if ($response = $event->getResponse()) {
            return $response;
        }

        return new Response($this->twig->render('@Core23LastFm/Auth/error.html.twig'));
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route         The name of the route
     * @param array  $parameters    An array of parameters
     * @param int    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     */
    private function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
}
