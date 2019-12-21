<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('core23_lastfm');

        $rootNode = $treeBuilder->getRootNode();

        \assert($rootNode instanceof ArrayNodeDefinition);

        $this->addApiSection($rootNode);
        $this->addHttpClientSection($rootNode);

        return $treeBuilder;
    }

    private function addApiSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('app_id')->isRequired()->end()
                        ->scalarNode('shared_secret')->isRequired()->end()
                        ->scalarNode('endpoint')->defaultValue('http://ws.audioscrobbler.com/2.0/')->end()
                        ->scalarNode('auth_url')->defaultValue('http://www.last.fm/api/auth/')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addHttpClientSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('http')
                    ->children()
                        ->scalarNode('client')->isRequired()->end()
                        ->scalarNode('message_factory')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
