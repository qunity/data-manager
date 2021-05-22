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

use BadMethodCallException;
use Qunity\Component\DataManager;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\AbstractDataManager
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
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
                [],
                new DataManager(),
            ], [
                ['key_1' => [1 => 'value'], 'key_2' => [2 => 'value'], 'key_3' => [3 => 'value']],
                new DataManager(['key_1' => [1 => 'value'], 'key_2' => [2 => 'value'], 'key_3' => [3 => 'value']]),
            ],
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
            ['0/0/0/0', new DataManager([[[[1 => 'value_error']]]]), 'value'],
            ['key/key/0/0', new DataManager(['key' => ['key' => [[1 => 'value_error']]]]), 'value'],
        ];
    }

    /**
     * @return array[]
     */
    public function providerSuccessMagicMethods(): array
    {
        return [
            [
                new DataManager(['key' => ['value']]),
                new DataManager(),
                'setKey_0',
                'value',
            ], [
                new DataManager(['key' => ['key_1' => 'value', 'key_2' => ['value']]]),
                new DataManager(['key' => ['key_1' => 'value', 'key_2' => ['value_error']]]),
                'setKey_Key2_0',
                'value',
            ], [
                new DataManager(['key' => ['value_1', 'value_2']]),
                new DataManager(['key' => ['value_1']]),
                'addKey',
                'value_2',
            ], [
                new DataManager(['key_1' => 'value', 'key_2' => ['value_1', 'value_2']]),
                new DataManager(['key_1' => 'value', 'key_2' => ['value_1']]),
                'addKey2',
                'value_2',
            ], [
                'value',
                new DataManager(['key' => ['value']]),
                'getKey_0',
            ], [
                'default',
                new DataManager(['key' => ['value']]),
                'getKey_5',
                'default',
            ], [
                true,
                new DataManager(['key' => ['value']]),
                'hasKey_0',
            ], [
                false,
                new DataManager(['key' => ['value']]),
                'hasKey_5',
            ], [
                new DataManager(),
                new DataManager(['key' => ['value']]),
                'delKey',
            ], [
                true,
                new DataManager(['key' => ['key' => ['value']]]),
                'tryKey_key_0',
                fn($value) => $value === 'value',
            ], [
                false,
                new DataManager(['key' => ['key' => []]]),
                'tryKey_key_0',
            ],
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
                'nonExistent',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerSingleMethods(): array
    {
        return [
            [
                new DataManager(),
                ['id' => '0/0', 'value' => 'value_1'],
                ['id' => 0, 'value' => 'value_2'],
                ['id' => '0', 'value' => ['value_1', 'value_2']],
            ], [
                new DataManager(),
                ['id' => 'key/0/0/0', 'value' => 'value_1'],
                ['id' => 'key/0/0', 'value' => 'value_2'],
                ['id' => 'key/0/0', 'value' => ['value_1', 'value_2']],
            ], [
                new DataManager(),
                ['id' => 'key/0/0/0', 'value' => 'value_error'],
                ['id' => 'key/0/0/0', 'value' => 'value'],
                ['id' => 'key/0/0', 'value' => ['value']],
            ], [
                new DataManager(),
                ['id' => 'key/0/0', 'value' => ['key' => 'value_error']],
                ['id' => 'key/0', 'value' => [['key' => 'value']]],
                ['id' => 'key/0/0', 'value' => ['key' => 'value']],
            ], [
                new DataManager(),
                ['id' => 'key/0/0/0', 'value' => 'value_1'],
                ['id' => 'key/0/0', 'value' => ['value_2']],
                ['id' => 'key/0/0', 'value' => ['value_1', 'value_2']],
            ], [
                new DataManager(),
                ['id' => 'key/0/0/key', 'value' => 'value_error'],
                ['id' => 'key/0/0', 'value' => ['key' => 'value']],
                ['id' => 'key/0/0', 'value' => ['key' => 'value']],
            ], [
                new DataManager(),
                ['id' => 'key/0/0', 'value' => ['key' => 'value_error']],
                ['id' => 'key/0', 'value' => [['key' => 'value']]],
                ['id' => 'key/0/0', 'value' => ['key' => 'value']],
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
                new DataManager(),
                [
                    'data' => [
                        'key_1' => 'value',
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/1' => 'value_2101',
                        'key_3/key_31' => [
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112'],
                        ],
                    ],
                    'real' => [
                        'key_1' => 'value',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101']]],
                        'key_3' => [
                            'key_31' => [
                                ['value_3100', 'value_3101'],
                                ['key_1' => 'value_3111', 'key_2' => 'value_3112'],
                            ],
                        ],
                    ],
                    'ids' => [
                        'key_1',
                        'key_2/key_21/0/0',
                        'key_2/key_21/0/1',
                        'key_3/key_31',
                        'key_3/key_31/0/0',
                        'key_3/key_31/0/1',
                        'key_3/key_31/1/key_1',
                        'key_3/key_31/1/key_2',
                    ],
                    'null' => [
                        'key_1' => null,
                        'key_2' => ['key_21' => [[null, null]]],
                        'key_3' => ['key_31' => [[null, null], ['key_1' => null, 'key_2' => null]]],
                    ],
                    'default' => [
                        'key_1' => 'default',
                        'key_2' => ['key_21' => [['default', 'default']]],
                        'key_3' => [
                            'key_31' => [
                                ['default', 'default'],
                                ['key_1' => 'default', 'key_2' => 'default'],
                            ],
                        ],
                    ],
                ],
                [
                    'data' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0' => 'value_2102',
                        'key_3/key_31' => [['value_3102'], ['key_3' => 'value_3113']],
                    ],
                    'real' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101', 'value_2102']]],
                        'key_3' => [
                            'key_31' => [
                                ['value_3100', 'value_3101', 'value_3102'],
                                ['key_1' => 'value_3111', 'key_2' => 'value_3112', 'key_3' => 'value_3113'],
                            ],
                        ],
                    ],
                    'ids' => [
                        'key_1',
                        'key_2/key_21/0/0',
                        'key_2/key_21/0/1',
                        'key_2/key_21/0/2',
                        'key_3/key_31/0/0',
                        'key_3/key_31/0/1',
                        'key_3/key_31/0/2',
                        'key_3/key_31/1/key_1',
                        'key_3/key_31/1/key_2',
                        'key_3/key_31/1/key_3',
                    ],
                    'null' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101', 'value_2102']]],
                        'key_3' => [
                            'key_31' => [
                                ['value_3100', 'value_3101', 'value_3102'],
                                ['key_1' => 'value_3111', 'key_2' => 'value_3112', 'key_3' => 'value_3113'],
                            ],
                        ],
                    ],
                    'default' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101', 'value_2102']]],
                        'key_3' => [
                            'key_31' => [
                                ['value_3100', 'value_3101', 'value_3102'],
                                ['key_1' => 'value_3111', 'key_2' => 'value_3112', 'key_3' => 'value_3113'],
                            ],
                        ],
                    ],
                ],
                [
                    'data' => [
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/2' => 'value_2102',
                        'key_3/key_31/0/0' => 'value_3100',
                        'key_3/key_31/0/2' => 'value_3102',
                        'key_3/key_31/1/key_2' => 'value_3112',
                        'key_3/key_31/1/key_3' => 'value_3113',
                    ],
                    'real' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [[1 => 'value_2101']]],
                        'key_3' => ['key_31' => [[1 => 'value_3101'], ['key_1' => 'value_3111']]],
                    ],
                    'ids' => [
                        'key_2/key_21/0/0',
                        'key_2/key_21/0/2',
                        'key_3/key_31/0/0',
                        'key_3/key_31/0/2',
                        'key_3/key_31/1/key_2',
                        'key_3/key_31/1/key_3',
                    ],
                    'null' => [
                        'key_2' => ['key_21' => [[0 => null, 2 => null]]],
                        'key_3' => ['key_31' => [[0 => null, 2 => null], ['key_2' => null, 'key_3' => null]]],
                    ],
                    'default' => [
                        'key_2' => ['key_21' => [[0 => 'default', 2 => 'default']]],
                        'key_3' => ['key_31' => [
                            [0 => 'default', 2 => 'default'],
                            ['key_2' => 'default', 'key_3' => 'default']],
                        ],
                    ],
                ],
            ],
        ];
    }
}
