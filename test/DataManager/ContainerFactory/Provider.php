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

namespace Qunity\UnitTest\Component\DataManager\ContainerFactory;

use LogicException;
use Qunity\Component\DataManager\Container;
use Qunity\Component\DataManager\ContainerInterface;
use stdClass;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\ContainerFactory
 */
trait Provider
{
    /**
     * @return array[]
     */
    public function providerSuccessCreate(): array
    {
        return [
            [
                ContainerInterface::class,
                new Container(),
                [],
                Container::class
            ], [
                ContainerInterface::class,
                new Container(['value']),
                ['value'],
                Container::class
            ], [
                ContainerInterface::class,
                new AnotherContainer(['value_1', 'value_2']),
                ['value_1', 'value_2'],
                AnotherContainer::class
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerErrorCreate(): array
    {
        return [
            [
                LogicException::class,
                'Class ' . stdClass::class . ' does not implement the interface ' . ContainerInterface::class,
                [],
                stdClass::class
            ],
        ];
    }
}
