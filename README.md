LastFmBundle
============
[![Latest Stable Version](https://poser.pugx.org/core23/lastfm-bundle/v/stable)](https://packagist.org/packages/core23/lastfm-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/lastfm-bundle/v/unstable)](https://packagist.org/packages/core23/lastfm-bundle)
[![License](https://poser.pugx.org/core23/lastfm-bundle/license)](https://packagist.org/packages/core23/lastfm-bundle)

[![Total Downloads](https://poser.pugx.org/core23/lastfm-bundle/downloads)](https://packagist.org/packages/core23/lastfm-bundle)
[![Monthly Downloads](https://poser.pugx.org/core23/lastfm-bundle/d/monthly)](https://packagist.org/packages/core23/lastfm-bundle)
[![Daily Downloads](https://poser.pugx.org/core23/lastfm-bundle/d/daily)](https://packagist.org/packages/core23/lastfm-bundle)

[![Continuous Integration](https://github.com/core23/LastFmBundle/workflows/Continuous%20Integration/badge.svg)](https://github.com/core23/LastFmBundle/actions)
[![Code Coverage](https://codecov.io/gh/core23/LastFmBundle/branch/master/graph/badge.svg)](https://codecov.io/gh/core23/LastFmBundle)

This bundle provides a wrapper for using the [Last.fm API] inside symfony.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require core23/lastfm-bundle
# To define a default http client and message factory
composer require symfony/http-client nyholm/psr7
```

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Core23\LastFmBundle\Core23LastFmBundle::class => ['all' => true],
];
```

### Configure the Bundle

Define the API credentials in your configuration.

```yaml
# config/packages/core23_lastfm.yaml

core23_lastfm:
    api:
        app_id:         "%lastfm_api.id%"
        shared_secret:  "%lastfm_api.secret%"

    http:
        client: 'httplug.client'
        message_factory: 'nyholm.psr7.psr17_factory'
```

## License

This bundle is under the [MIT license](LICENSE.md).

[Last.fm API]: http://www.last.fm/api
