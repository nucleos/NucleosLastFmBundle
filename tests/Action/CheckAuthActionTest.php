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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class CheckAuthActionTest extends TestCase
{
    private $router;

    private $sessionManager;

    private $authService;

    protected function setUp(): void
    {
        $this->router         = $this->createMock(RouterInterface::class);
        $this->sessionManager = $this->createMock(SessionManagerInterface::class);
        $this->authService    = $this->createMock(AuthServiceInterface::class);
    }

    public function testExecute(): void
    {
        $this->sessionManager->expects(self::once())->method('store');

        $this->router->method('generate')->with('nucleos_lastfm_success')
            ->willReturn('/success')
        ;

        $this->authService->method('createSession')->with('MY_TOKEN')
            ->willReturn($this->createMock(SessionInterface::class))
        ;

        $request = new Request();
        $request->query->set('token', 'MY_TOKEN');

        $action = new CheckAuthAction(
            $this->router,
            $this->sessionManager,
            $this->authService
        );

        $response = $action($request);

        self::assertSame('/success', $response->getTargetUrl());
    }

    public function testExecuteWithNoToken(): void
    {
        $this->router->method('generate')->with('nucleos_lastfm_auth')
            ->willReturn('/auth')
        ;

        $request = new Request();
        $request->query->set('token', '');

        $action = new CheckAuthAction(
            $this->router,
            $this->sessionManager,
            $this->authService
        );

        $response = $action($request);

        self::assertSame('/auth', $response->getTargetUrl());
    }

    public function testExecuteWithNoSession(): void
    {
        $this->router->method('generate')->with('nucleos_lastfm_error')
            ->willReturn('/error')
        ;
        $this->authService->method('createSession')->with('MY_TOKEN')
            ->willReturn(null)
        ;

        $request = new Request();
        $request->query->set('token', 'MY_TOKEN');

        $action = new CheckAuthAction(
            $this->router,
            $this->sessionManager,
            $this->authService
        );

        $response = $action($request);

        self::assertSame('/error', $response->getTargetUrl());
    }
}
