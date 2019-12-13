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
            return new RedirectResponse($this->router->generate('core23_lastfm_auth'));
        }

        // Store session
        $lastFmSession = $this->authService->createSession($token);

        if (null === $lastFmSession) {
            return new RedirectResponse($this->router->generate('core23_lastfm_error'));
        }

        $this->sessionManager->store($lastFmSession);

        return new RedirectResponse($this->router->generate('core23_lastfm_success'));
    }
}
