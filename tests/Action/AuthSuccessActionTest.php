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
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class AuthSuccessActionTest extends TestCase
{
    use ProphecyTrait;

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

        $this->twig->render('@NucleosLastFm/Auth/success.html.twig', [
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

        $this->router->generate('nucleos_lastfm_error')
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

        $this->router->generate('nucleos_lastfm_error')
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
