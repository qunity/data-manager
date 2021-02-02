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

namespace Qunity\Component;

use LogicException;

/**
 * Class DataManagerFactory
 * @package Qunity\Component
 */
class DataManagerFactory
{
    /**
     * Create new data manager instance
     *
     * @param array $containers
     * @param string $class
     *
     * @return DataManagerInterface
     * @throws LogicException
     */
    public static function create(array $containers = [], string $class = DataManager::class): DataManagerInterface
    {
        $instance = new $class($containers);
        if (!($instance instanceof DataManagerInterface)) {
            $interface = DataManagerInterface::class;
            throw new LogicException("Class ${class} does not implement the interface ${interface}");
        }
        return $instance;
    }
}
