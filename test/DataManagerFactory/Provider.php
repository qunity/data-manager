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

namespace Qunity\UnitTest\Component\DataManagerFactory;

use LogicException;
use Qunity\Component\DataManager;
use Qunity\Component\DataManagerInterface;
use stdClass;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManagerFactory
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
                DataManagerInterface::class,
                new DataManager(),
                [],
                DataManager::class
            ], [
                DataManagerInterface::class,
                new DataManager(),
                [],
                null
            ], [
                DataManagerInterface::class,
                new AnotherDataManager(['another' => ['key' => ['value']]]),
                ['another' => ['key' => ['value']]],
                AnotherDataManager::class
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
                'Class ' . stdClass::class . ' does not implement the interface ' . DataManagerInterface::class,
                [],
                stdClass::class
            ],
        ];
    }
}
