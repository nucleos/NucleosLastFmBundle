<?php

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

class ConfigurationTest extends TestCase
{
    public function testOptions()
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), array(array(
            'api' => array(
                'app_id'        => 'foo_id',
                'shared_secret' => 'bar_secret',
            ),
        )));

        $expected = array(
            'api' => array(
                'app_id'        => 'foo_id',
                'shared_secret' => 'bar_secret',
                'endpoint'      => 'http://ws.audioscrobbler.com/2.0/',
                'auth_url'      => 'http://www.last.fm/api/auth/',
            ),
            'auth_success' => array(
                'route'            => null,
                'route_parameters' => array(),
            ),
            'auth_error' => array(
                'route'            => null,
                'route_parameters' => array(),
            ),
            'http' => array(
                'client'          => 'httplug.client.default',
                'message_factory' => 'httplug.message_factory.default',
            ),
        );

        $this->assertSame($expected, $config);
    }
}
