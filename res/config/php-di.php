<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FOS\Message\Driver\DriverInterface;
use FOS\Message\Repository;
use FOS\Message\RepositoryInterface;
use FOS\Message\Sender;
use FOS\Message\SenderInterface;
use FOS\Message\Tagger;
use FOS\Message\TaggerInterface;
use Interop\Container\ContainerInterface;

/**
 * Configuration file to auto-register FOSMessage services in PHP-DI using Puli.
 *
 * @see http://docs.puli.io/en/latest/
 * @see https://github.com/PHP-DI/Kernel
 */

return [

    RepositoryInterface::class => function(ContainerInterface $container) {
        if (! $container->has(DriverInterface::class)) {
            throw new \RuntimeException(
                'You need to configure the FOSMessage driver in PHP-DI to use the library. '.
                'Use the definition name "'. DriverInterface::class .'". See the documentation for more details.'
            );
        }

        return new Repository($container->get(DriverInterface::class));
    },

    SenderInterface::class => function(ContainerInterface $container) {
        if (! $container->has(DriverInterface::class)) {
            throw new \RuntimeException(
                'You need to configure the FOSMessage driver in PHP-DI to use the library. '.
                'Use the definition name "'. DriverInterface::class .'". See the documentation for more details.'
            );
        }

        return new Sender($container->get(DriverInterface::class));
    },

    TaggerInterface::class => function(ContainerInterface $container) {
        if (! $container->has(DriverInterface::class)) {
            throw new \RuntimeException(
                'You need to configure the FOSMessage driver in PHP-DI to use the library. '.
                'Use the definition name "'. DriverInterface::class .'". See the documentation for more details.'
            );
        }

        return new Tagger($container->get(DriverInterface::class), $container->get(RepositoryInterface::class));
    },

];
