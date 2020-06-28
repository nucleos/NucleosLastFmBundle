<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\LastFmBundle;

final class NucleosLastFmEvents
{
    public const AUTH_SUCCESS = 'nucleos_lastfm.event.auth.success';
    public const AUTH_ERROR   = 'nucleos_lastfm.event.auth.error';
}
