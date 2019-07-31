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
use Core23\LastFmBundle\Session\SessionManagerInterface;
use Core23\LastFmBundle\Tests\EventDispatcher\TestEventDispatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class AuthSuccessActionTest extends TestCase
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

        $this->twig->render('@Core23LastFm/Auth/success.html.twig', [
            'name' => 'FooUser',
        ])->shouldBeCalled();

        $action = new AuthSuccessAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher
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

        $this->eventDispatcher->setResponse($eventResponse);

        $action = new AuthSuccessAction(
            $this->twig->reveal(),
            $this->router->reveal(),
            $this->sessionManager->reveal(),
            $this->eventDispatcher
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
            $this->eventDispatcher
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
            $this->eventDispatcher
        );

        static::assertInstanceOf(RedirectResponse::class, $action());
    }
}
