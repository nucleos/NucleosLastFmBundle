<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests;

use Nucleos\LastFmBundle\DependencyInjection\NucleosLastFmExtension;
use Nucleos\LastFmBundle\NucleosLastFmBundle;
use PHPUnit\Framework\TestCase;

final class NucleosLastFmBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new NucleosLastFmBundle();

        static::assertInstanceOf(NucleosLastFmExtension::class, $bundle->getContainerExtension());
    }
}
