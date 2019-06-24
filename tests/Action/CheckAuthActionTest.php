<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Action;

use Core23\LastFm\Service\AuthServiceInterface;
use Core23\LastFm\Session\SessionInterface;
use Core23\LastFmBundle\Action\CheckAuthAction;
use Core23\LastFmBundle\Session\SessionManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class CheckAuthActionTest extends TestCase
{
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

        $this->router->generate('core23_lastfm_success', [], UrlGeneratorInterface::ABSOLUTE_PATH)
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
        $this->router->generate('core23_lastfm_auth', [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/auth')
        ;

        $request = new Request();
        $request->query->set('token', null);

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
        $this->router->generate('core23_lastfm_error', [], UrlGeneratorInterface::ABSOLUTE_PATH)
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
