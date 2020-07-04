<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\LastFm\Service\AuthServiceInterface;
use Nucleos\LastFmBundle\Action\AuthErrorAction;
use Nucleos\LastFmBundle\Action\AuthSuccessAction;
use Nucleos\LastFmBundle\Action\CheckAuthAction;
use Nucleos\LastFmBundle\Action\StartAuthAction;
use Nucleos\LastFmBundle\Session\SessionManagerInterface;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(StartAuthAction::class)
            ->public()
            ->args([
                new Reference(AuthServiceInterface::class),
                new Reference('router'),
            ])

        ->set(AuthErrorAction::class)
            ->public()
            ->args([
                new Reference('twig'),
                new Reference('router'),
                new Reference(SessionManagerInterface::class),
                new Reference('event_dispatcher'),
            ])

        ->set(AuthSuccessAction::class)
            ->public()
            ->args([
                new Reference('twig'),
                new Reference('router'),
                new Reference(SessionManagerInterface::class),
                new Reference('event_dispatcher'),
            ])

        ->set(CheckAuthAction::class)
            ->public()
            ->args([
                new Reference('router'),
                new Reference(SessionManagerInterface::class),
                new Reference(AuthServiceInterface::class),
            ])

        ;
};
