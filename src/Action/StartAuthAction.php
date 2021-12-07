<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Action;

use Nucleos\LastFm\Service\AuthServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class StartAuthAction
{
    private AuthServiceInterface $authService;

    private RouterInterface $router;

    public function __construct(AuthServiceInterface $authService, RouterInterface $router)
    {
        $this->authService = $authService;
        $this->router      = $router;
    }

    public function __invoke(): RedirectResponse
    {
        $callbackUrl = $this->router->generate('nucleos_lastfm_check', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return new RedirectResponse($this->authService->getAuthUrl($callbackUrl));
    }
}
