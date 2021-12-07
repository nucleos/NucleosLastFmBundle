<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\Action;

use Nucleos\LastFm\Session\SessionInterface;
use Nucleos\LastFmBundle\Action\AuthSuccessAction;
use Nucleos\LastFmBundle\Session\SessionManagerInterface;
use Nucleos\LastFmBundle\Tests\EventDispatcher\TestEventDispatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class AuthSuccessActionTest extends TestCase
{
    private $twig;

    private $router;

    private $sessionManager;

    private TestEventDispatcher $eventDispatcher;

    protected function setUp(): void
    {
        $this->twig            = $this->createMock(Environment::class);
        $this->router          = $this->createMock(RouterInterface::class);
        $this->sessionManager  = $this->createMock(SessionManagerInterface::class);
        $this->eventDispatcher = new TestEventDispatcher();
    }

    public function testExecute(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $this->sessionManager->method('isAuthenticated')
            ->willReturn(true)
        ;
        $this->sessionManager->method('getSession')
            ->willReturn($session)
        ;
        $this->sessionManager->method('getUsername')
            ->willReturn('FooUser')
        ;

        $this->twig->expects(static::once())->method('render')->with('@NucleosLastFm/Auth/success.html.twig', [
            'name' => 'FooUser',
        ]);

        $action = new AuthSuccessAction(
            $this->twig,
            $this->router,
            $this->sessionManager,
            $this->eventDispatcher
        );

        $response = $action();

        static::assertNotInstanceOf(RedirectResponse::class, $response);
        static::assertSame(200, $response->getStatusCode());
    }

    public function testExecuteWithCaughtEvent(): void
    {
        $session = $this->createMock(SessionInterface::class);

        $this->sessionManager->method('isAuthenticated')
            ->willReturn(true)
        ;
        $this->sessionManager->method('getSession')
            ->willReturn($session)
        ;
        $this->sessionManager->method('getUsername')
            ->willReturn('FooUser')
        ;

        $eventResponse = new Response();

        $this->eventDispatcher->setResponse($eventResponse);

        $action = new AuthSuccessAction(
            $this->twig,
            $this->router,
            $this->sessionManager,
            $this->eventDispatcher
        );

        $response = $action();

        static::assertSame($eventResponse, $response);
    }

    public function testExecuteNoAuth(): void
    {
        $this->sessionManager->method('isAuthenticated')
            ->willReturn(false)
        ;

        $this->router->expects(static::once())->method('generate')->with('nucleos_lastfm_error')
            ->willReturn('/success')
        ;

        $action = new AuthSuccessAction(
            $this->twig,
            $this->router,
            $this->sessionManager,
            $this->eventDispatcher
        );

        static::assertInstanceOf(RedirectResponse::class, $action());
    }

    public function testExecuteNoSession(): void
    {
        $this->sessionManager->method('isAuthenticated')
            ->willReturn(true)
        ;
        $this->sessionManager->method('getSession')
            ->willReturn(null)
        ;

        $this->router->expects(static::once())->method('generate')->with('nucleos_lastfm_error')
            ->willReturn('/success')
        ;

        $action = new AuthSuccessAction(
            $this->twig,
            $this->router,
            $this->sessionManager,
            $this->eventDispatcher
        );

        static::assertInstanceOf(RedirectResponse::class, $action());
    }
}
