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

namespace Qunity\UnitTest\Component\DataManager\Helper\Recursive;

use LogicException;
use Qunity\Component\DataManager;
use Qunity\Component\DataManager\ConfigurableInterface;
use Qunity\Component\DataManagerFactory;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
 */
trait Provider
{
    /**
     * @return array[]
     */
    public function providerSuccessConfigure(): array
    {
        $expected = function (): object {
            /** @var AnotherDataManager $object1 */
            $object1 = DataManagerFactory::create(['key' => 'value'], AnotherDataManager::class);
            /** @var AnotherDataManager $object2 */
            $object2 = DataManagerFactory::create(['key' => 'config_value'], AnotherDataManager::class);
            /** @var AnotherDataManager $object3 */
            $object3 = DataManagerFactory::create(['key' => 'config_value'], AnotherDataManager::class);
            return $object1->setObjects([
                'config_1' => $object2->setObjects([DataManagerFactory::create(['key' => 'config_value'])]),
                'config_2' => $object3->setObjects([DataManagerFactory::create(['key' => 'config_value'])])
            ]);
        };
        return [
            [
                [],
                []
            ], [
                [
                    DataManagerFactory::create(),
                    DataManagerFactory::create(['key' => 'value']),
                    DataManagerFactory::create(['key' => 'value'], AnotherDataManager::class)
                ],
                [
                    [],
                    ['data' => ['key' => 'value']],
                    ['data' => ['key' => 'value'], 'class' => AnotherDataManager::class]
                ]
            ], [
                [
                    $expected(),
                    $expected()
                ],
                [
                    [
                        'class' => AnotherDataManager::class,
                        'data' => ['key' => 'value'],
                        'config' => [
                            'config_1' => [
                                'class' => AnotherDataManager::class,
                                'data' => ['key' => 'config_value'],
                                'config' => [
                                    ['data' => ['key' => 'config_value']]
                                ]
                            ],
                            'config_2' => [
                                'class' => AnotherDataManager::class,
                                'data' => ['key' => 'config_value'],
                                'config' => [
                                    ['data' => ['key' => 'config_value']]
                                ]
                            ]
                        ]
                    ],
                    DataManagerFactory::create([
                        'class' => AnotherDataManager::class,
                        'data' => ['key' => 'value'],
                        'config' => DataManagerFactory::create([
                            'config_1' => [
                                'class' => AnotherDataManager::class,
                                'data' => ['key' => 'config_value'],
                                'config' => DataManagerFactory::create([
                                    ['data' => ['key' => 'config_value']]
                                ])
                            ],
                            'config_2' => [
                                'class' => AnotherDataManager::class,
                                'data' => ['key' => 'config_value'],
                                'config' => DataManagerFactory::create([
                                    ['data' => ['key' => 'config_value']]
                                ])
                            ]
                        ])
                    ])
                ]
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerErrorConfigure(): array
    {
        return [
            [
                LogicException::class,
                'Class ' . DataManager::class . ' does not implement the interface ' . ConfigurableInterface::class,
                [['config' => []]]
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerJoin(): array
    {
        return [
            [
                null,
            ], [
                ['key' => 'value'],
                ['key' => 'value'],
                ['key' => 'value'],
            ], [
                ['key_1' => 'value_1', 'key_2' => 'value_2'],
                ['key_1' => 'value_1'],
                ['key_2' => 'value_2'],
            ], [
                ['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value'],
                ['key_1' => 'value_error'],
                ['key_2' => 'value_error'],
                ['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value'],
            ], [
                ['value_1', 'value_2'],
                ['value_1'],
                'value_2',
            ], [
                DataManagerFactory::create(['key_1' => 'value_1', 'key_2' => 'value_2']),
                ['key_1' => 'value_1'],
                DataManagerFactory::create(['key_2' => 'value_2']),
            ], [
                DataManagerFactory::create(['key' => 'value']),
                ['key' => 'value_error'],
                DataManagerFactory::create(['key' => 'value']),
            ], [
                DataManagerFactory::create(['key_1' => 'value_1', 'key_2' => 'value_2']),
                DataManagerFactory::create(['key_1' => 'value_1']),
                ['key_2' => 'value_2'],
            ], [
                DataManagerFactory::create(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value']),
                ['key_1' => 'value_1'],
                DataManagerFactory::create(['key_2' => 'value_2']),
                DataManagerFactory::create(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value']),
            ], [
                'value',
                DataManagerFactory::create(['value_error']),
                'value',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerSet(): array
    {
        return [
            [
                ['value'],
                [],
                'value_error',
                ['value']
            ], [
                ['key' => 'value'],
                ['key'],
                'value',
                []
            ], [
                ['key' => ['value'], 2 => 'value'],
                [0, 'key'],
                'value',
                ['key' => ['value_error'], 2 => 'value']
            ], [
                ['key' => DataManagerFactory::create(['value'])],
                [0, 'key'],
                'value',
                ['key' => DataManagerFactory::create([['value_error']])]
            ], [
                ['key' => DataManagerFactory::create([['value_1', 'value_2']])],
                [0, 0, 'key'],
                'value_1',
                ['key' => DataManagerFactory::create([['value_error', 'value_2']])]
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerAdd(): array
    {
        return [
            [
                ['value'],
                [],
                'value_error',
                ['value']
            ], [
                ['key' => [1 => 'value']],
                [1, 'key'],
                'value',
                []
            ], [
                ['key' => ['value'], 2 => 'value_2'],
                ['key'],
                ['value'],
                ['key' => [], 2 => 'value_2']
            ], [
                ['key' => 'value', 2 => 'value_2'],
                ['key'],
                'value',
                ['key' => 'value_error', 2 => 'value_2']
            ], [
                ['key' => ['value_1', 'value_2'], 2 => 'value_2'],
                ['key'],
                'value_2',
                ['key' => ['value_1'], 2 => 'value_2']
            ], [
                ['key' => DataManagerFactory::create([['key' => 'value']])],
                ['key', 0, 'key'],
                'value',
                ['key' => DataManagerFactory::create([['key' => 'value_error']])]
            ], [
                ['key' => DataManagerFactory::create([['key' => ['value_1', 'value_2']]])],
                ['key', 0, 'key'],
                'value_2',
                ['key' => DataManagerFactory::create([['key' => ['value_1']]])]
            ], [
                ['key' => DataManagerFactory::create([['key' => null]])],
                ['key', 0, 'key'],
                null,
                ['key' => DataManagerFactory::create([['key' => 'value']])]
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGet(): array
    {
        return [
            [null, [], ['value'], null],
            ['default', [], ['value'], 'default'],
            ['value_1', [0, 'key'], ['key' => ['value_1', 'value_2']], null],
            [null, [5, 'key'], ['key' => ['value_1', 'value_2']], null],
            ['default', [5, 'key'], ['key' => ['value_1', 'value_2']], 'default'],
            ['value_1', [0, 'key'], ['key' => ['value_1', 'value_2']], null],
            ['value', ['key', 0, 'key'], ['key' => DataManagerFactory::create([['key' => 'value']])], null],
            ['value', ['key', 0, 'key'], ['key' => DataManagerFactory::create([['key' => 'value']])], 'default'],
            [null, ['key', 5, 'key'], ['key' => DataManagerFactory::create([['key' => 'value']])], null],
            ['default', ['key', 5, 'key'], ['key' => DataManagerFactory::create([['key' => 'value']])], 'default'],
        ];
    }

    /**
     * @return array[]
     */
    public function providerHas(): array
    {
        return [
            [false, [], ['value']],
            [true, [0, 'key'], ['key' => ['value_1', 'value_2']]],
            [false, [5, 'key'], ['key' => ['value_1', 'value_2']]],
            [true, ['key', 0, 'key'], ['key' => DataManagerFactory::create([['key' => 'value']])]],
            [false, ['key', 5, 'key'], ['key' => DataManagerFactory::create([['key' => 'value']])]],
        ];
    }

    /**
     * @return array[]
     */
    public function providerDel(): array
    {
        return [
            [
                ['value'],
                [],
                ['value']
            ], [
                [],
                [0],
                ['value']
            ], [
                ['key' => ['value']],
                [1, 'key'],
                ['key' => ['value', 'value_error']]
            ], [
                ['key' => DataManagerFactory::create(['value'])],
                [1, 'key'],
                ['key' => DataManagerFactory::create(['value', 'value_error'])]
            ], [
                ['key' => DataManagerFactory::create(['value_1', 'value_2'])],
                [5, 'key'],
                ['key' => DataManagerFactory::create(['value_1', 'value_2'])]
            ],
        ];
    }
}
