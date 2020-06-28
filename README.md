NucleosLastFmBundle
===================
[![Latest Stable Version](https://poser.pugx.org/nucleos/lastfm-bundle/v/stable)](https://packagist.org/packages/nucleos/lastfm-bundle)
[![Latest Unstable Version](https://poser.pugx.org/nucleos/lastfm-bundle/v/unstable)](https://packagist.org/packages/nucleos/lastfm-bundle)
[![License](https://poser.pugx.org/nucleos/lastfm-bundle/license)](https://packagist.org/packages/nucleos/lastfm-bundle)

[![Total Downloads](https://poser.pugx.org/nucleos/lastfm-bundle/downloads)](https://packagist.org/packages/nucleos/lastfm-bundle)
[![Monthly Downloads](https://poser.pugx.org/nucleos/lastfm-bundle/d/monthly)](https://packagist.org/packages/nucleos/lastfm-bundle)
[![Daily Downloads](https://poser.pugx.org/nucleos/lastfm-bundle/d/daily)](https://packagist.org/packages/nucleos/lastfm-bundle)

[![Continuous Integration](https://github.com/nucleos/NucleosLastFmBundle/workflows/Continuous%20Integration/badge.svg)](https://github.com/nucleos/NucleosLastFmBundle/actions)
[![Code Coverage](https://codecov.io/gh/nucleos/NucleosLastFmBundle/branch/master/graph/badge.svg)](https://codecov.io/gh/nucleos/NucleosLastFmBundle)

This bundle provides a wrapper for using the [Last.fm API] inside symfony.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require nucleos/lastfm-bundle
# To define a default http client and message factory
composer require symfony/http-client nyholm/psr7
```

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Nucleos\LastFmBundle\NucleosLastFmBundle::class => ['all' => true],
];
```

### Configure the Bundle

Define the API credentials in your configuration.

```yaml
# config/packages/nucleos_lastfm.yaml

nucleos_lastfm:
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
