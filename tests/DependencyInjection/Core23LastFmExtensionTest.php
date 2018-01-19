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

class Core23LastFmExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->load([
            'api' => [
                'app_id'        => 'foo_id',
                'shared_secret' => 'bar_secret',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('core23_lastfm.auth_success.redirect_route');
        $this->assertContainerBuilderHasParameter('core23_lastfm.auth_success.redirect_route_params', []);
        $this->assertContainerBuilderHasParameter('core23_lastfm.auth_error.redirect_route');
        $this->assertContainerBuilderHasParameter('core23_lastfm.auth_error.redirect_route_params', []);

        $this->assertContainerBuilderHasParameter('core23_lastfm.api.app_id', 'foo_id');
        $this->assertContainerBuilderHasParameter('core23_lastfm.api.shared_secret', 'bar_secret');
        $this->assertContainerBuilderHasParameter('core23_lastfm.api.endpoint', 'http://ws.audioscrobbler.com/2.0/');
        $this->assertContainerBuilderHasParameter('core23_lastfm.api.auth_url', 'http://www.last.fm/api/auth/');

        $this->assertContainerBuilderHasAlias('core23_lastfm.http.client', 'httplug.client.default');
        $this->assertContainerBuilderHasAlias('core23_lastfm.http.message_factory', 'httplug.message_factory.default');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new Core23LastFmExtension(),
        ];
    }
}
