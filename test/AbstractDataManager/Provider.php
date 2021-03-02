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

use ArrayIterator;
use Qunity\Component\DataManagerFactory;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
trait Provider
{
    /**
     * @return array[]
     */
    public function providerGetIterator(): array
    {
        return [
            [
                new ArrayIterator([]),
                DataManagerFactory::create()
            ], [
                new ArrayIterator(['key_1' => 'value_1', 'key_2' => 'value_2', 'key_3' => 'value_3']),
                DataManagerFactory::create(['key_1' => 'value_1', 'key_2' => 'value_2', 'key_3' => 'value_3'])
            ],
        ];
    }

    /**
     * @return array
     */
    public function providerArrayAccess(): array
    {
        return [
            [0, 'value'],
            ['key', 'value'],
            ['0/0/0/0', 'value'],
            ['key_1/key_2/0/0', 'value'],
            ['_//key_1 / / / key_2/0/0//_', 'value'],
            ['_//key_1 / / / KEY_2/0/0//_', 'value'],
        ];
    }

    /**
     * @return array
     */
    public function providerSingleMethods(): array
    {
        return [
            [
                ['0/0', 'value_1'],
                [0, 'value_2'],
                ['0', ['value_1', 'value_2']]
            ], [
                ['key/0/0/0', 'value_1'],
                ['key/0/0', 'value_2'],
                ['key/0/0', ['value_1', 'value_2']]
            ], [
                ['key/0/0/0', 'value_error'],
                ['key/0/0/0', 'value'],
                ['key/0/0', ['value']]
            ], [
                ['key/0/0', ['key' => 'value_error']],
                ['key/0', [['key' => 'value']]],
                ['key/0/0', ['key' => 'value']]
            ], [
                ['key/0/0/0', 'value_1'],
                ['key/0/0', DataManagerFactory::create(['value_2'])],
                ['key/0/0', DataManagerFactory::create(['value_1', 'value_2'])]
            ], [
                ['key/0/0/key', 'value_error'],
                ['key/0/0', DataManagerFactory::create(['key' => 'value'])],
                ['key/0/0', DataManagerFactory::create(['key' => 'value'])]
            ], [
                ['key/0/0', ['key' => 'value_error']],
                ['key/0', [DataManagerFactory::create(['key' => 'value'])]],
                ['key/0/0', DataManagerFactory::create(['key' => 'value'])]
            ],
        ];
    }

    /**
     * @return array
     */
    public function providerMassMethods(): array
    {
        return [
            [
                [
                    'data' => [
                        'key_1' => 'value',
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/1' => 'value_2101',
                        'key_3/key_31' => DataManagerFactory::create([
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                        ])
                    ],
                    'flat' => [
                        'key_1' => 'value',
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/1' => 'value_2101',
                        'key_3/key_31' => DataManagerFactory::create([
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                        ])
                    ],
                    'real' => [
                        'key_1' => 'value',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101']]],
                        'key_3' => [
                            'key_31' => DataManagerFactory::create([
                                ['value_3100', 'value_3101'],
                                ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                            ])
                        ]
                    ]
                ],
                [
                    'data' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0' => 'value_2102',
                        'key_3/key_31' => DataManagerFactory::create([
                            ['value_3102'],
                            ['key_3' => 'value_3113']
                        ])
                    ],
                    'flat' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0' => ['value_2100', 'value_2101', 'value_2102'],
                        'key_3/key_31' => DataManagerFactory::create([
                            ['value_3100', 'value_3101', 'value_3102'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112', 'key_3' => 'value_3113']
                        ])
                    ],
                    'real' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101', 'value_2102']]],
                        'key_3' => [
                            'key_31' => DataManagerFactory::create([
                                ['value_3100', 'value_3101', 'value_3102'],
                                ['key_1' => 'value_3111', 'key_2' => 'value_3112', 'key_3' => 'value_3113']
                            ])
                        ]
                    ]
                ],
                [
                    'data' => [
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/2' => 'value_2102',
                        'key_3/key_31/0/0' => 'value_3100',
                        'key_3/key_31/0/2' => 'value_3102',
                        'key_3/key_31/1/key_2' => 'value_3112',
                        'key_3/key_31/1/key_3' => 'value_3113'
                    ],
                    'flat' => null,
                    'real' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [[1 => 'value_2101']]],
                        'key_3' => [
                            'key_31' => DataManagerFactory::create([
                                [1 => 'value_3101'],
                                ['key_1' => 'value_3111']
                            ])
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public function providerMagicMethods(): array
    {
        ($object11 = DataManagerFactory::create())->set(['key' => ['value']]);
        $object12 = DataManagerFactory::create();

        ($object21 = DataManagerFactory::create([], AnotherDataManager::class))
            ->set(['key_1' => 'value', 'key_2' => ['value']]);
        $object22 = DataManagerFactory::create(
            ['key_1' => 'value', 'key_2' => ['value_error']],
            AnotherDataManager::class
        );

        ($object31 = DataManagerFactory::create())->set(['key' => ['value_1', 'value_2']]);
        ($object32 = DataManagerFactory::create())->set('key/0', 'value_1');

        ($object41 = DataManagerFactory::create([], AnotherDataManager::class))
            ->set(['key_1' => 'value', 'key_2' => ['value_1', 'value_2']]);
        ($object42 = DataManagerFactory::create([], AnotherDataManager::class))
            ->set(['key_1' => 'value', 'key_2/0' => 'value_1']);

        ($object5 = DataManagerFactory::create())->set('key/0', 'value');
        ($object6 = DataManagerFactory::create([], AnotherDataManager::class))->set('key/0', 'value');

        ($object7 = DataManagerFactory::create());
        ($object8 = DataManagerFactory::create([], AnotherDataManager::class));

        return [
            [$object11, $object12, 'setKey_0', 'value'],
            [$object21, $object22, 'setKey2_0', 'value'],
            [$object31, $object32, 'addKey', 'value_2'],
            [$object41, $object42, 'addKey2', 'value_2'],
            ['value', $object5, 'getKey_0'],
            ['default', $object5, 'getKey_5', 'default'],
            ['value', $object6, 'getKey_0'],
            ['default', $object6, 'getKey_5', 'default'],
            [true, $object5, 'hasKey_0'],
            [false, $object5, 'hasKey_5'],
            [true, $object6, 'hasKey_0'],
            [false, $object6, 'hasKey_5'],
            [$object7, $object5, 'delKey'],
            [$object8, $object6, 'delKey'],
        ];
    }
}
