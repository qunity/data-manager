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

namespace Qunity\UnitTest\Component\AbstractDataManager\NonRecursive;

use BadMethodCallException;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\AbstractDataManager\NonRecursive
 */
trait Provider
{
    /**
     * @return array[]
     */
    public function providerGetIterator(): array
    {
        $data = ['key_1/1' => 'value', 'key_2/2' => 'value', 'key_3/3' => 'value'];
        return [
            [[], new DataManager()],
            [$data, new DataManager($data)],
        ];
    }

    /**
     * @return array[]
     */
    public function providerArrayAccess(): array
    {
        return [
            [0, new DataManager(), 'value'],
            ['key', new DataManager(['key_error' => 'value_error']), 'value'],
            ['0/0/0/0', new DataManager(['0/0/0/1' => 'value_error']), 'value'],
            ['key/key/0/0', new DataManager(['key/key/0/1' => 'value_error']), 'value'],
            [' _//_KEY//key//0//0_//_ ', new DataManager(['key/key/0/1' => 'value_error']), 'value'],
        ];
    }

    /**
     * @return array[]
     */
    public function providerSuccessMagicMethods(): array
    {
        $object11 = new DataManager(['key/0' => 'value']);
        $object12 = new DataManager();

        $object21 = new DataManager(['key/key_1' => 'value', 'key/key_2/0' => 'value']);
        $object22 = new DataManager(['key/key_1' => 'value', 'key/key_2/0' => 'value_error']);

        $object31 = new DataManager(['key/1' => 'value_1', 'key/2' => 'value_2']);
        $object32 = new DataManager(['key/1' => 'value_1']);

        $object41 = new DataManager(['key_1' => 'value', 'key_2/1' => 'value_1', 'key_2/2' => 'value_2']);
        $object42 = new DataManager(['key_1' => 'value', 'key_2/1' => 'value_1']);

        $object51 = new DataManager();
        $object52 = new DataManager(['key/0' => 'value']);

        return [
            [$object11, $object12, 'setKey_0', 'value'],
            [$object21, $object22, 'setKey_Key2_0', 'value'],
            [$object31, $object32, 'addKey_2', 'value_2'],
            [$object41, $object42, 'addKey2_2', 'value_2'],
            ['value', $object52, 'getKey_0'],
            ['default', $object52, 'getKey_5', 'default'],
            [true, $object52, 'hasKey_0'],
            [false, $object52, 'hasKey_5'],
            [$object51, $object52, 'delKey_0'],
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

    /**
     * @return array[]
     */
    public function providerSingleMethods(): array
    {
        $object = new DataManager();
        return [
            [
                $object,
                ['path' => 'key/0/0/0', 'value' => 'value_error'],
                ['path' => 'key/0/0/0', 'value' => 'value'],
                ['path' => 'key/0/0/0', 'value' => 'value']
            ], [
                $object,
                ['path' => 'key/0/0/0', 'value' => ['value_1']],
                ['path' => 'key/0/0/0', 'value' => ['value_2']],
                ['path' => 'key/0/0/0', 'value' => ['value_1', 'value_2']]
            ], [
                $object,
                ['path' => 'key/0/0/0', 'value' => ['key_1' => 'value_1', 'key_2' => 'value_error']],
                ['path' => 'key/0/0/0', 'value' => ['key_2' => 'value_2']],
                ['path' => 'key/0/0/0', 'value' => ['key_1' => 'value_1', 'key_2' => 'value_2']]
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerMassMethods(): array
    {
        $object = new DataManager();
        return [
            [
                $object,
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
                    'real' => [
                        'key_1' => 'value',
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/1' => 'value_2101',
                        'key_3/key_31' => new DataManager([
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                        ])
                    ],
                    'paths' => [
                        'key_1',
                        'key_2/key_21/0/0',
                        'key_2/key_21/0/1',
                        'key_3/key_31'
                    ],
                    'null' => [
                        'key_1' => null,
                        'key_2/key_21/0/0' => null,
                        'key_2/key_21/0/1' => null,
                        'key_3/key_31' => null
                    ],
                    'default' => [
                        'key_1' => 'default',
                        'key_2/key_21/0/0' => 'default',
                        'key_2/key_21/0/1' => 'default',
                        'key_3/key_31' => 'default'
                    ]
                ],
                [
                    'data' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0/0' => 'value_2102_replace',
                        'key_2/key_21/0/1' => 'value_2101_replace',
                        'key_3/key_31' => 'value_31'
                    ],
                    'real' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0/0' => 'value_2102_replace',
                        'key_2/key_21/0/1' => 'value_2101_replace',
                        'key_3/key_31' => 'value_31'
                    ],
                    'paths' => [
                        'key_1',
                        'key_2/key_21/0/0',
                        'key_2/key_21/0/1',
                        'key_3/key_31'
                    ],
                    'null' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0/0' => 'value_2102_replace',
                        'key_2/key_21/0/1' => 'value_2101_replace',
                        'key_3/key_31' => 'value_31'
                    ],
                    'default' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0/0' => 'value_2102_replace',
                        'key_2/key_21/0/1' => 'value_2101_replace',
                        'key_3/key_31' => 'value_31'
                    ]
                ],
                [
                    'data' => [
                        'key_2/key_21/0/0' => 'value_2102_replace',
                        'key_2/key_21/0/1' => 'value_2101_replace',
                    ],
                    'real' => [
                        'key_2/key_21/0/0' => 'value_2102_replace',
                        'key_2/key_21/0/1' => 'value_2101_replace',
                    ],
                    'paths' => ['key_1', 'key_3/key_31'],
                    'null' => ['key_1' => null, 'key_3/key_31' => null],
                    'default' => ['key_1' => 'default', 'key_3/key_31' => 'default']
                ]
            ],
        ];
    }
}
