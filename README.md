What is LastFmBundle?
=============================
[![Latest Stable Version](https://poser.pugx.org/core23/lastfm-bundle/v/stable)](https://packagist.org/packages/core23/lastfm-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/lastfm-bundle/v/unstable)](https://packagist.org/packages/core23/lastfm-bundle)
[![Build Status](http://img.shields.io/travis/core23/LastFmBundle.svg)](http://travis-ci.org/core23/LastFmBundle)
[![License](http://img.shields.io/packagist/l/core23/lastfm-bundle.svg)](https://packagist.org/packages/core23/lastfm-bundle)


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

This bundle is available under the [MIT license](LICENSE.md).

[HTTPlug]: http://docs.php-http.org/en/latest/index.html
[Last.fm API]: http://www.last.fm/api
