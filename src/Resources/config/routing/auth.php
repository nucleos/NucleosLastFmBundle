<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Loader\Configurator;

use Nucleos\LastFmBundle\Action\AuthErrorAction;
use Nucleos\LastFmBundle\Action\AuthSuccessAction;
use Nucleos\LastFmBundle\Action\CheckAuthAction;
use Nucleos\LastFmBundle\Action\StartAuthAction;

return static function (RoutingConfigurator $routes): void {
    $routes->add('nucleos_lastfm_success', '/success')
        ->controller(AuthSuccessAction::class)
        ->methods(['GET'])
    ;

    $routes->add('nucleos_lastfm_error', '/error')
        ->controller(AuthErrorAction::class)
        ->methods(['GET'])
    ;

    $routes->add('nucleos_lastfm_check', '/check')
        ->controller(CheckAuthAction::class)
        ->methods(['GET', 'POST'])
    ;

    $routes->add('nucleos_lastfm_auth', '/')
        ->controller(StartAuthAction::class)
        ->methods(['GET'])
    ;
};
