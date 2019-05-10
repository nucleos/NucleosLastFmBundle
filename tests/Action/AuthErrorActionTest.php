<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Action;

use Core23\LastFmBundle\Action\AuthErrorAction;
use Core23\LastFmBundle\Core23LastFmEvents;
use Core23\LastFmBundle\Event\AuthFailedEvent;
use Core23\LastFmBundle\Session\SessionManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class AuthErrorActionTest extends TestCase
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
        $this->eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
    }

    public function testExecute(): void
    {
        $this->sessionManager->isAuthenticated()
            ->willReturn(false)
        ;

        $this->sessionManager->clear()
            ->shouldBeCalled()
        ;

        $this->eventDispatcher->dispatch(Core23LastFmEvents::AUTH_ERROR, Argument::type(AuthFailedEvent::class))
            ->shouldBeCalled()
        ;

        $action = new AuthErrorAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher->reveal()
        );

        $response = $action();

        static::assertNotInstanceOf(RedirectResponse::class, $response);
        static::assertSame(200, $response->getStatusCode());
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

        $this->eventDispatcher->dispatch(Core23LastFmEvents::AUTH_ERROR, Argument::type(AuthFailedEvent::class))
            ->will(function ($args) use ($eventResponse) {
                $args[1]->setResponse($eventResponse);
            })
        ;

        $action = new AuthErrorAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher->reveal()
        );

        $response = $action();

        static::assertSame($eventResponse, $response);
    }

    public function testExecuteWithNoAuth(): void
    {
        $this->sessionManager->isAuthenticated()
            ->willReturn(true)
        ;

        $this->router->generate('core23_lastfm_success', [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/success')
            ->shouldBeCalled()
        ;

        $action = new AuthErrorAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher->reveal()
        );

        static::assertInstanceOf(RedirectResponse::class, $action());
    }
}
