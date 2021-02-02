<?php

/**
 * This file is part of the Qunity package.
 *
 * Copyright (c) Rodion Kachkin <kyleRQWS@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Qunity\Component\DataManager;

use LogicException;

/**
 * Class ContainerFactory
 * @package Qunity\Component\DataManager
 */
class ContainerFactory
{
    /**
     * Create new container instance
     *
     * @param array $data
     * @param string $class
     *
     * @return ContainerInterface
     * @throws LogicException
     */
    public static function create(array $data = [], string $class = Container::class): ContainerInterface
    {
        $instance = new $class($data);
        if (!($instance instanceof ContainerInterface)) {
            $interface = ContainerInterface::class;
            throw new LogicException("Class ${class} does not implement the interface ${interface}");
        }
        return $instance;
    }
}
