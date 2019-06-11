<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Action;

use Core23\LastFm\Session\SessionInterface;
use Core23\LastFmBundle\Action\AuthSuccessAction;
use Core23\LastFmBundle\Core23LastFmEvents;
use Core23\LastFmBundle\Event\AuthSuccessEvent;
use Core23\LastFmBundle\Session\SessionManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

class AuthSuccessActionTest extends TestCase
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
        $session = $this->prophesize(SessionInterface::class);

        $this->sessionManager->isAuthenticated()
            ->willReturn(true)
        ;
        $this->sessionManager->getSession()
            ->willReturn($session)
        ;
        $this->sessionManager->getUsername()
            ->willReturn('FooUser')
        ;

        $this->eventDispatcher->dispatch(Argument::type(AuthSuccessEvent::class), Core23LastFmEvents::AUTH_SUCCESS)
            ->shouldBeCalled()
        ;

        $this->twig->render('@Core23LastFm/Auth/success.html.twig', [
            'name' => 'FooUser',
        ])->shouldBeCalled();

        $action = new AuthSuccessAction(
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
        $session = $this->prophesize(SessionInterface::class);

        $this->sessionManager->isAuthenticated()
            ->willReturn(true)
        ;
        $this->sessionManager->getSession()
            ->willReturn($session)
        ;
        $this->sessionManager->getUsername()
            ->willReturn('FooUser')
        ;

        $eventResponse = new Response();

        $this->eventDispatcher->dispatch(Argument::type(AuthSuccessEvent::class), Core23LastFmEvents::AUTH_SUCCESS)
            ->will(function ($args) use ($eventResponse) {
                $args[0]->setResponse($eventResponse);
            })
        ;

        $action = new AuthSuccessAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher->reveal()
        );

        $response = $action();

        static::assertSame($eventResponse, $response);
    }

    public function testExecuteNoAuth(): void
    {
        $this->sessionManager->isAuthenticated()
            ->willReturn(false)
        ;

        $this->router->generate('core23_lastfm_error', [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/success')
            ->shouldBeCalled()
        ;

        $action = new AuthSuccessAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher->reveal()
        );

        static::assertInstanceOf(RedirectResponse::class, $action());
    }

    public function testExecuteNoSession(): void
    {
        $this->sessionManager->isAuthenticated()
            ->willReturn(true)
        ;
        $this->sessionManager->getSession()
            ->willReturn(null)
        ;

        $this->router->generate('core23_lastfm_error', [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/success')
            ->shouldBeCalled()
        ;

        $action = new AuthSuccessAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher->reveal()
        );

        static::assertInstanceOf(RedirectResponse::class, $action());
    }
}
