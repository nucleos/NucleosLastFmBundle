<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle;

final class Core23LastFmEvents
{
    public const AUTH_SUCCESS = 'core23_lastfm.event.auth.success';
    public const AUTH_ERROR   = 'core23_lastfm.event.auth.error';
}
