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
use Nucleos\LastFm\Session\SessionInterface;
use Nucleos\LastFmBundle\Action\CheckAuthAction;
use Nucleos\LastFmBundle\Session\SessionManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class CheckAuthActionTest extends TestCase
{
    use ProphecyTrait;

    private $router;

    private $sessionManager;

    private $authService;

    protected function setUp(): void
    {
        $this->router         = $this->prophesize(RouterInterface::class);
        $this->sessionManager = $this->prophesize(SessionManagerInterface::class);
        $this->authService    = $this->prophesize(AuthServiceInterface::class);
    }

    public function testExecute(): void
    {
        $this->sessionManager->store(Argument::type(SessionInterface::class))
            ->shouldBeCalled()
        ;

        $this->router->generate('nucleos_lastfm_success')
            ->willReturn('/success')
        ;

        $this->authService->createSession('MY_TOKEN')
            ->willReturn($this->prophesize(SessionInterface::class))
        ;

        $request = new Request();
        $request->query->set('token', 'MY_TOKEN');

        $action = new CheckAuthAction(
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->authService->reveal()
        );

        $response = $action($request);

        static::assertSame('/success', $response->getTargetUrl());
    }

    public function testExecuteWithNoToken(): void
    {
        $this->router->generate('nucleos_lastfm_auth')
            ->willReturn('/auth')
        ;

        $request = new Request();
        $request->query->set('token', '');

        $action = new CheckAuthAction(
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->authService->reveal()
        );

        $response = $action($request);

        static::assertSame('/auth', $response->getTargetUrl());
    }

    public function testExecuteWithNoSession(): void
    {
        $this->router->generate('nucleos_lastfm_error')
            ->willReturn('/error')
        ;
        $this->authService->createSession('MY_TOKEN')
            ->willReturn(null)
        ;

        $request = new Request();
        $request->query->set('token', 'MY_TOKEN');

        $action = new CheckAuthAction(
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->authService->reveal()
        );

        $response = $action($request);

        static::assertSame('/error', $response->getTargetUrl());
    }
}
