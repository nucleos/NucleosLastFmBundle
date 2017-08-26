What is LastFmBundle?
=============================
[![Latest Stable Version](https://poser.pugx.org/core23/lastfm-bundle/v/stable)](https://packagist.org/packages/core23/lastfm-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/lastfm-bundle/v/unstable)](https://packagist.org/packages/core23/lastfm-bundle)
[![License](https://poser.pugx.org/core23/lastfm-bundle/license)](https://packagist.org/packages/core23/lastfm-bundle)

[![Build Status](https://travis-ci.org/core23/LastFmBundle.svg)](https://travis-ci.org/core23/LastFmBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/core23/LastFmBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/core23/LastFmBundle)
[![Code Climate](https://codeclimate.com/github/core23/LastFmBundle/badges/gpa.svg)](https://codeclimate.com/github/core23/LastFmBundle)
[![Coverage Status](https://coveralls.io/repos/core23/LastFmBundle/badge.svg)](https://coveralls.io/r/core23/LastFmBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a1a7ce1f-7508-4022-a5ff-323ce044fff3/mini.png)](https://insight.sensiolabs.com/projects/a1a7ce1f-7508-4022-a5ff-323ce044fff3)

[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/core23)
[![Donate to this project using PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://paypal.me/gripp)

This bundle provides a wrapper for using the [Last.fm API] inside symfony.

### Installation

```
php composer.phar require core23/lastfm-bundle
php composer.phar require php-http/guzzle6-adapter # if you want to use Guzzle
```

### Enabling the bundle

```php
    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            
            new Http\HttplugBundle\HttplugBundle(),
            new Core23\LastFmBundle\Core23LastFmBundle(),

            // ...
        );
    }
```

Define the API credentials in your configuration.

```yml
    # config.yml

    core23_last_fm:
        api:
            app_id:         %lastfm_api.id%
            shared_secret:  %lastfm_api.secret%
```

Define a [HTTPlug] client in your configuration.

```yml
    # config.yml
    
    httplug:
        classes:
            client: Http\Adapter\Guzzle6\Client
            message_factory: Http\Message\MessageFactory\GuzzleMessageFactory
            uri_factory: Http\Message\UriFactory\GuzzleUriFactory
            stream_factory: Http\Message\StreamFactory\GuzzleStreamFactory
        clients:
            default:
                # ...
                plugins:
                    - httplug.plugin.redirect # plugin is needed for the webcrawler
```

It is recommended to use a cache to reduce the API usage.

```yml
    httplug:
        plugins:
            cache:
                cache_pool: 'acme.httplug_cache'
                config:
                    default_ttl: 7200 # Two hours
        clients:
            default:
                plugins:
                    - httplug.plugin.cache
```

This bundle is available under the [MIT license](LICENSE.md).

[HTTPlug]: http://docs.php-http.org/en/latest/index.html
[Last.fm API]: http://www.last.fm/api
