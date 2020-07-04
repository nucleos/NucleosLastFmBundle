<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class NucleosLastFmExtension extends Extension
{
    public function getAlias()
    {
        return 'nucleos_lastfm';
    }

    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
        $bundles       = $container->getParameter('kernel.bundles');

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($bundles['TwigBundle'])) {
            $loader->load('action.php');
        }

        $loader->load('services.php');

        $this->configureApi($container, $config);
        $this->configureHttpClient($container, $config);
    }

    /**
     * @param array<mixed> $config
     */
    private function configureApi(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('nucleos_lastfm.api.app_id', $config['api']['app_id']);
        $container->setParameter('nucleos_lastfm.api.shared_secret', $config['api']['shared_secret']);
        $container->setParameter('nucleos_lastfm.api.endpoint', $config['api']['endpoint']);
        $container->setParameter('nucleos_lastfm.api.auth_url', $config['api']['auth_url']);
    }

    /**
     * @param array<mixed> $config
     */
    private function configureHttpClient(ContainerBuilder $container, array $config): void
    {
        $container->setAlias('nucleos_lastfm.http.client', $config['http']['client']);
        $container->setAlias('nucleos_lastfm.http.message_factory', $config['http']['message_factory']);
    }
}
