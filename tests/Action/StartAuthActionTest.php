<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Action;

use Core23\LastFm\Service\AuthServiceInterface;
use Core23\LastFmBundle\Action\StartAuthAction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class StartAuthActionTest extends TestCase
{
    private $authService;

    private $router;

    protected function setUp(): void
    {
        $this->authService = $this->prophesize(AuthServiceInterface::class);
        $this->router      = $this->prophesize(RouterInterface::class);
    }

    public function testExecute(): void
    {
        $this->router->generate('core23_lastfm_check', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('/start')
        ;

        $this->authService->getAuthUrl('/start')
            ->willReturn('https://lastFm/login')
        ;

        $action = new StartAuthAction(
            $this->authService->reveal(),
            $this->router->reveal()
        );

        static::assertSame('https://lastFm/login', $action()->getTargetUrl());
    }
}
