<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\LastFmBundle;

use Core23\LastFmBundle\DependencyInjection\Core23LastFmExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class Core23LastFmBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new Core23LastFmExtension();
        }

        return $this->extension;
    }
}
