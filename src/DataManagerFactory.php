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
     * Create new manager object
     *
     * @param array<int|string,mixed> $data
     * @param string|null $class
     *
     * @return DataManagerInterface
     */
    public static function create(array $data = [], string $class = null): DataManagerInterface
    {
        if ($class === null) {
            $class = DataManager::class;
        }
        $object = new $class($data); // TODO: fix for getting through object-manager
        if (!($object instanceof DataManagerInterface)) {
            $class = $object::class;
            $interface = DataManagerInterface::class;
            throw new LogicException("Class $class does not implement the interface $interface");
        }
        return $object;
    }
}
