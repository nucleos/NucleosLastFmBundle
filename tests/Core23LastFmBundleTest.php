<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests;

use Core23\LastFmBundle\Core23LastFmBundle;
use Core23\LastFmBundle\DependencyInjection\Core23LastFmExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class Core23LastFmBundleTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $bundle = new Core23LastFmBundle();

        static::assertInstanceOf(BundleInterface::class, $bundle);
    }

    public function testGetContainerExtension(): void
    {
        $bundle = new Core23LastFmBundle();

        static::assertInstanceOf(Core23LastFmExtension::class, $bundle->getContainerExtension());
    }
}
