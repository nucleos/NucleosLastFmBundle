<?php

/*
 * This file is part of the ni-ju-san CMS.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class Core23LastFmExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->configureRoutes($container, $config);
        $this->configureApi($container, $config);
        $this->configureHttpClient($container, $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function configureRoutes(ContainerBuilder $container, array $config)
    {
        $container->setParameter('core23.lastfm.auth_success.redirect_route', $config['auth_success']['route']);
        $container->setParameter('core23.lastfm.auth_success.redirect_route_params', $config['auth_success']['route_parameters']);

        $container->setParameter('core23.lastfm.auth_error.redirect_route', $config['auth_success']['route']);
        $container->setParameter('core23.lastfm.auth_error.redirect_route_params', $config['auth_success']['route_parameters']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function configureApi(ContainerBuilder $container, array $config)
    {
        $container->setParameter('core23.lastfm.api.app_id', $config['api']['app_id']);
        $container->setParameter('core23.lastfm.api.shared_secret', $config['api']['shared_secret']);
        $container->setParameter('core23.lastfm.api.endpoint', $config['api']['endpoint']);
        $container->setParameter('core23.lastfm.api.auth_url', $config['api']['auth_url']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function configureHttpClient(ContainerBuilder $container, array $config)
    {
        $container->setAlias('core23.lastfm.http.client', $config['http']['client']);
        $container->setAlias('core23.lastfm.http.message_factory', $config['http']['message_factory']);
    }
}
