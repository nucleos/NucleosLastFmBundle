<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\Tests\DependencyInjection;

use Core23\LastFmBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testOptions(): void
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), [[
            'api' => [
                'app_id'        => 'foo_id',
                'shared_secret' => 'bar_secret',
            ],
        ]]);

        $expected = [
            'api' => [
                'app_id'        => 'foo_id',
                'shared_secret' => 'bar_secret',
                'endpoint'      => 'http://ws.audioscrobbler.com/2.0/',
                'auth_url'      => 'http://www.last.fm/api/auth/',
            ],
            'http' => [
                'client'          => null,
                'message_factory' => null,
            ],
        ];

        static::assertSame($expected, $config);
    }
}
