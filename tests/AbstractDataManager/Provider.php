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
use BadMethodCallException;

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
                new DataManager()
            ], [
                new ArrayIterator(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value']),
                new DataManager(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value'])
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerArrayAccess(): array
    {
        return [
            [0, 'value'],
            ['key', 'value'],
            ['0/0/0/0', 'value'],
            ['key/key/0/0', 'value'],
            ['_//key / / / key/0/0//_', 'value'],
            [' _//KEY / / / KEY/0/0//_ ', 'value'],
        ];
    }

    /**
     * @return array[]
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
                ['key/0/0', new DataManager(['value_2'])],
                ['key/0/0', new DataManager(['value_1', 'value_2'])]
            ], [
                ['key/0/0/key', 'value_error'],
                ['key/0/0', new DataManager(['key' => 'value'])],
                ['key/0/0', new DataManager(['key' => 'value'])]
            ], [
                ['key/0/0', ['key' => 'value_error']],
                ['key/0', [new DataManager(['key' => 'value'])]],
                ['key/0/0', new DataManager(['key' => 'value'])]
            ],
        ];
    }

    /**
     * @return array[]
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
                        'key_3/key_31' => new DataManager([
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                        ])
                    ],
                    'flat' => [
                        'key_1' => 'value',
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/1' => 'value_2101',
                        'key_3/key_31' => new DataManager([
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                        ])
                    ],
                    'real' => [
                        'key_1' => 'value',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101']]],
                        'key_3' => [
                            'key_31' => new DataManager([
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
                        'key_3/key_31' => new DataManager([
                            ['value_3102'],
                            ['key_3' => 'value_3113']
                        ])
                    ],
                    'flat' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0' => ['value_2100', 'value_2101', 'value_2102'],
                        'key_3/key_31' => new DataManager([
                            ['value_3100', 'value_3101', 'value_3102'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112', 'key_3' => 'value_3113']
                        ])
                    ],
                    'real' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101', 'value_2102']]],
                        'key_3' => [
                            'key_31' => new DataManager([
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
                            'key_31' => new DataManager([
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
     * @return array[]
     */
    public function providerSuccessMagicMethods(): array
    {
        ($object11 = new DataManager())->set(['key' => ['value']]);
        $object12 = new DataManager();

        ($object21 = new DataManager())
            ->set(['key_1' => 'value', 'key_2' => ['value']]);
        $object22 = new DataManager(['key_1' => 'value', 'key_2' => ['value_error']]);

        ($object31 = new DataManager())->set(['key' => ['value_1', 'value_2']]);
        ($object32 = new DataManager())->set('key/0', 'value_1');

        ($object41 = new DataManager())
            ->set(['key_1' => 'value', 'key_2' => ['value_1', 'value_2']]);
        ($object42 = new DataManager())
            ->set(['key_1' => 'value', 'key_2/0' => 'value_1']);

        ($object5 = new DataManager())->set('key/0', 'value');
        ($object6 = new DataManager())->set('key/0', 'value');

        ($object7 = new DataManager());
        ($object8 = new DataManager());

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

    /**
     * @return array[]
     */
    public function providerErrorMagicMethods(): array
    {
        return [
            [
                BadMethodCallException::class,
                'Call to invalid method ' . DataManager::class . '::nonExistent',
                new DataManager(),
                'nonExistent'
            ],
        ];
    }
}
