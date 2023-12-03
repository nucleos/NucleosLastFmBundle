<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\Action;

use Nucleos\LastFm\Service\AuthServiceInterface;
use Nucleos\LastFmBundle\Action\StartAuthAction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class StartAuthActionTest extends TestCase
{
    private $authService;

    private $router;

    protected function setUp(): void
    {
        $this->authService = $this->createMock(AuthServiceInterface::class);
        $this->router      = $this->createMock(RouterInterface::class);
    }

    public function testExecute(): void
    {
        $this->router->method('generate')->with('nucleos_lastfm_check', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('/start')
        ;

        $this->authService->method('getAuthUrl')->with('/start')
            ->willReturn('https://lastFm/login')
        ;

        $action = new StartAuthAction(
            $this->authService,
            $this->router
        );

        self::assertSame('https://lastFm/login', $action()->getTargetUrl());
    }
}
