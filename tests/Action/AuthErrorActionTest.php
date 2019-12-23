<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Action;

use Core23\LastFmBundle\Action\AuthErrorAction;
use Core23\LastFmBundle\Session\SessionManagerInterface;
use Core23\LastFmBundle\Tests\EventDispatcher\TestEventDispatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class AuthErrorActionTest extends TestCase
{
    private $twig;

    private $router;

    private $sessionManager;

    private $eventDispatcher;

    protected function setUp(): void
    {
        $this->twig            = $this->prophesize(Environment::class);
        $this->router          = $this->prophesize(RouterInterface::class);
        $this->sessionManager  = $this->prophesize(SessionManagerInterface::class);
        $this->eventDispatcher = new TestEventDispatcher();
    }

    public function testExecute(): void
    {
        $this->sessionManager->isAuthenticated()
            ->willReturn(false)
        ;

        $this->sessionManager->clear()
            ->shouldBeCalled()
        ;

        $action = new AuthErrorAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher
        );

        $response = $action();

        static::assertNotInstanceOf(RedirectResponse::class, $response);
        static::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testExecuteWithCaughtEvent(): void
    {
        $this->sessionManager->isAuthenticated()
            ->willReturn(false)
        ;

        $this->sessionManager->clear()
            ->shouldBeCalled()
        ;

        $eventResponse = new Response();

        $this->eventDispatcher->setResponse($eventResponse);

        $action = new AuthErrorAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher
        );

        $response = $action();

        static::assertSame($eventResponse, $response);
    }

    public function testExecuteWithNoAuth(): void
    {
        $this->sessionManager->isAuthenticated()
            ->willReturn(true)
        ;

        $this->router->generate('core23_lastfm_success')
            ->willReturn('/success')
            ->shouldBeCalled()
        ;

        $action = new AuthErrorAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher
        );

        static::assertInstanceOf(RedirectResponse::class, $action());
    }
}
