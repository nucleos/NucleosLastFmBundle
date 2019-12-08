<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\DependencyInjection;

use Core23\LastFmBundle\DependencyInjection\Core23LastFmExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

final class Core23LastFmExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
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

        $this->assertContainerBuilderHasParameter('core23_lastfm.api.app_id', 'foo_id');
        $this->assertContainerBuilderHasParameter('core23_lastfm.api.shared_secret', 'bar_secret');
        $this->assertContainerBuilderHasParameter('core23_lastfm.api.endpoint', 'http://ws.audioscrobbler.com/2.0/');
        $this->assertContainerBuilderHasParameter('core23_lastfm.api.auth_url', 'http://www.last.fm/api/auth/');

        $this->assertContainerBuilderHasAlias('core23_lastfm.http.client', 'acme.client');
        $this->assertContainerBuilderHasAlias('core23_lastfm.http.message_factory', 'acme.message_factory');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new Core23LastFmExtension(),
        ];
    }
}
