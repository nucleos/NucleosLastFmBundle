<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\Controller;

use Core23\LastFmBundle\Controller\AuthController;
use PHPUnit\Framework\TestCase;

final class AuthControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new AuthController();
    }

    public function testItIsInstantiable(): void
    {
        $this->assertNotNull($this->controller);
    }
}
