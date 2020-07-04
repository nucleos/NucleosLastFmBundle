<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\LastFm\Client\ApiClient;
use Nucleos\LastFm\Client\ApiClientInterface;
use Nucleos\LastFm\Connection\ConnectionInterface;
use Nucleos\LastFm\Connection\PsrClientConnection;
use Nucleos\LastFm\Crawler\ArtistEventCrawler;
use Nucleos\LastFm\Crawler\ArtistEventCrawlerInterface;
use Nucleos\LastFm\Crawler\EventInfoCrawler;
use Nucleos\LastFm\Crawler\EventInfoCrawlerInterface;
use Nucleos\LastFm\Crawler\EventListCrawler;
use Nucleos\LastFm\Crawler\EventListCrawlerInterface;
use Nucleos\LastFm\Crawler\UserEventCrawler;
use Nucleos\LastFm\Crawler\UserEventCrawlerInterface;
use Nucleos\LastFm\Service\AlbumService;
use Nucleos\LastFm\Service\AlbumServiceInterface;
use Nucleos\LastFm\Service\ArtistService;
use Nucleos\LastFm\Service\ArtistServiceInterface;
use Nucleos\LastFm\Service\AuthService;
use Nucleos\LastFm\Service\AuthServiceInterface;
use Nucleos\LastFm\Service\ChartService;
use Nucleos\LastFm\Service\ChartServiceInterface;
use Nucleos\LastFm\Service\GeoService;
use Nucleos\LastFm\Service\GeoServiceInterface;
use Nucleos\LastFm\Service\LibraryService;
use Nucleos\LastFm\Service\LibraryServiceInterface;
use Nucleos\LastFm\Service\TagService;
use Nucleos\LastFm\Service\TagServiceInterface;
use Nucleos\LastFm\Service\TrackService;
use Nucleos\LastFm\Service\TrackServiceInterface;
use Nucleos\LastFm\Service\UserService;
use Nucleos\LastFm\Service\UserServiceInterface;
use Nucleos\LastFmBundle\Session\SessionManager;
use Nucleos\LastFmBundle\Session\SessionManagerInterface;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(SessionManagerInterface::class, SessionManager::class)
            ->args([
                new Reference('session'),
            ])

        ->set(PsrClientConnection::class)
            ->args([
                new Reference('nucleos_lastfm.http.client'),
                new Reference('nucleos_lastfm.http.message_factory'),
                new Parameter('nucleos_lastfm.api.endpoint'),
            ])

        ->alias(ConnectionInterface::class, PsrClientConnection::class)

        ->set(ApiClientInterface::class, ApiClient::class)
            ->args([
                new Reference(ConnectionInterface::class),
                new Parameter('nucleos_lastfm.api.app_id'),
                new Parameter('nucleos_lastfm.api.shared_secret'),
            ])

        ->set(ArtistEventCrawlerInterface::class, ArtistEventCrawler::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(EventInfoCrawlerInterface::class, EventInfoCrawler::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(EventListCrawlerInterface::class, EventListCrawler::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(UserEventCrawlerInterface::class, UserEventCrawler::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(AlbumServiceInterface::class, AlbumService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(ArtistServiceInterface::class, ArtistService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(AuthServiceInterface::class, AuthService::class)
            ->args([
                new Reference(ConnectionInterface::class),
                new Parameter('nucleos_lastfm.api.auth_url'),
            ])

        ->set(ChartServiceInterface::class, ChartService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(GeoServiceInterface::class, GeoService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(LibraryServiceInterface::class, LibraryService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(TagServiceInterface::class, TagService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(TrackServiceInterface::class, TrackService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ->set(UserServiceInterface::class, UserService::class)
            ->args([
                new Reference(ConnectionInterface::class),
            ])

        ;
};
