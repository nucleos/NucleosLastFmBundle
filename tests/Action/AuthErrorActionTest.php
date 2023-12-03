<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\Action;

use Nucleos\LastFmBundle\Action\AuthErrorAction;
use Nucleos\LastFmBundle\Session\SessionManagerInterface;
use Nucleos\LastFmBundle\Tests\EventDispatcher\TestEventDispatcher;
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
        $this->sessionManager->method('isAuthenticated')
            ->willReturn(false)
        ;

        $this->sessionManager->expects(self::once())->method('clear');

        $this->twig->method('render')->with('@NucleosLastFm/Auth/error.html.twig')
            ->willReturn('CONTENT')
        ;

        $action = new AuthErrorAction(
            $this->twig,
            $this->router,
            $this->sessionManager,
            $this->eventDispatcher
        );

        $response = $action();

        self::assertNotInstanceOf(RedirectResponse::class, $response);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testExecuteWithCaughtEvent(): void
    {
        $this->sessionManager->method('isAuthenticated')
            ->willReturn(false)
        ;

        $this->sessionManager->expects(self::once())->method('clear');

        $eventResponse = new Response();

        $this->eventDispatcher->setResponse($eventResponse);

        $action = new AuthErrorAction(
            $this->twig,
            $this->router,
            $this->sessionManager,
            $this->eventDispatcher
        );

        $response = $action();

        self::assertSame($eventResponse, $response);
    }

    public function testExecuteWithNoAuth(): void
    {
        $this->sessionManager->method('isAuthenticated')
            ->willReturn(true)
        ;

        $this->router->expects(self::once())->method('generate')->with('nucleos_lastfm_success')
            ->willReturn('/success')
        ;

        $action = new AuthErrorAction(
            $this->twig,
            $this->router,
            $this->sessionManager,
            $this->eventDispatcher
        );

        self::assertInstanceOf(RedirectResponse::class, $action());
    }
}
