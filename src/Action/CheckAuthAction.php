<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Action;

use Core23\LastFm\Service\AuthServiceInterface;
use Core23\LastFmBundle\Session\SessionManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class CheckAuthAction
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var AuthServiceInterface
     */
    private $authService;

    public function __construct(RouterInterface $router, SessionManagerInterface $sessionManager, AuthServiceInterface $authService)
    {
        $this->router         = $router;
        $this->sessionManager = $sessionManager;
        $this->authService    = $authService;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $token = (string) $request->query->get('token', '');

        if ('' === $token) {
            return new RedirectResponse($this->generateUrl('core23_lastfm_auth'));
        }

        // Store session
        $lastFmSession = $this->authService->createSession($token);

        if (null === $lastFmSession) {
            return new RedirectResponse($this->generateUrl('core23_lastfm_error'));
        }

        $this->sessionManager->store($lastFmSession);

        return new RedirectResponse($this->generateUrl('core23_lastfm_success'));
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
