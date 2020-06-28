<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Nucleos\LastFmBundle\DependencyInjection\NucleosLastFmExtension;

final class NucleosLastFmExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->load([
            'api' => [
                'app_id'        => 'foo_id',
                'shared_secret' => 'bar_secret',
            ],
            'http' => [
                'client'          => 'acme.client',
                'message_factory' => 'acme.message_factory',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('nucleos_lastfm.api.app_id', 'foo_id');
        $this->assertContainerBuilderHasParameter('nucleos_lastfm.api.shared_secret', 'bar_secret');
        $this->assertContainerBuilderHasParameter('nucleos_lastfm.api.endpoint', 'http://ws.audioscrobbler.com/2.0/');
        $this->assertContainerBuilderHasParameter('nucleos_lastfm.api.auth_url', 'http://www.last.fm/api/auth/');

        $this->assertContainerBuilderHasAlias('nucleos_lastfm.http.client', 'acme.client');
        $this->assertContainerBuilderHasAlias('nucleos_lastfm.http.message_factory', 'acme.message_factory');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new NucleosLastFmExtension(),
        ];
    }
}
