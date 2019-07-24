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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class StartAuthAction
{
    /**
     * @var AuthServiceInterface
     */
    private $authService;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(AuthServiceInterface $authService, RouterInterface $router)
    {
        $this->authService = $authService;
        $this->router      = $router;
    }

    public function __invoke(): RedirectResponse
    {
        $callbackUrl = $this->generateUrl('core23_lastfm_check', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return new RedirectResponse($this->authService->getAuthUrl($callbackUrl));
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
