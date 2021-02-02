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

namespace Qunity\UnitTest\Component\AbstractDataManager;

use Qunity\Component\DataManager\Container;
use Qunity\Component\DataManager\ContainerFactory;
use Qunity\Component\DataManagerFactory;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
trait Provider
{
    /**
     * @return array
     */
    public function providerContainer(): array
    {
        return [
            [
                Container::class,
                [],
                '',
                []
            ], [
                AnotherContainer::class,
                [],
                'another',
                ['class' => AnotherContainer::class]
            ], [
                AnotherContainer::class,
                ['test' => 'value'],
                'another',
                ['data' => ['test' => 'value'], 'class' => AnotherContainer::class]
            ], [
                AnotherContainer::class,
                ['test' => 'value'],
                'main',
                ContainerFactory::create(['test' => 'value'], AnotherContainer::class)
            ],
        ];
    }

    /**
     * @return array
     */
    public function providerMagicMethods(): array
    {
        ($dataManager11 = DataManagerFactory::create())->container()->setElements(['key' => ['value']]);
        $dataManager12 = DataManagerFactory::create();

        ($dataManager21 = DataManagerFactory::create([], AnotherDataManager::class))
            ->container()->setElements(['test' => 'value', 'key' => ['value']]);
        $dataManager22 = DataManagerFactory::create([], AnotherDataManager::class);

        ($dataManager31 = DataManagerFactory::create())->container()->setElements(['key' => ['value_1', 'value_2']]);
        ($dataManager32 = DataManagerFactory::create())->container()->setElement('key/0', 'value_1');

        ($dataManager41 = DataManagerFactory::create([], AnotherDataManager::class))
            ->container()->setElements(['test' => 'value', 'key' => ['value_1', 'value_2']]);
        ($dataManager42 = DataManagerFactory::create([], AnotherDataManager::class))
            ->container()->setElement('key/0', 'value_1');

        ($dataManager5 = DataManagerFactory::create())->container()->setElement('key/0', 'value');
        ($dataManager6 = DataManagerFactory::create([], AnotherDataManager::class))
            ->container()->setElement('key/0', 'value');

        ($dataManager7 = DataManagerFactory::create())->container();
        ($dataManager8 = DataManagerFactory::create([], AnotherDataManager::class))->container();

        return [
            [$dataManager11, $dataManager12, 'setMain_Key_0', 'value'],
            [$dataManager21, $dataManager22, 'setAnother_Key_0', 'value'],
            [$dataManager31, $dataManager32, 'addMain_Key', 'value_2'],
            [$dataManager41, $dataManager42, 'addAnother_Key', 'value_2'],
            ['value', $dataManager5, 'getMain_Key_0'],
            ['default', $dataManager5, 'getMain_Key_5', 'default'],
            ['value', $dataManager6, 'getAnother_Key_0'],
            ['default', $dataManager6, 'getAnother_Key_5', 'default'],
            [true, $dataManager5, 'hasMain_Key_0'],
            [false, $dataManager5, 'hasMain_Key_5'],
            [true, $dataManager6, 'hasAnother_Key_0'],
            [false, $dataManager6, 'hasAnother_Key_5'],
            [$dataManager7, $dataManager5, 'delMain_Key'],
            [$dataManager8, $dataManager6, 'delAnother_Key'],
        ];
    }
}
