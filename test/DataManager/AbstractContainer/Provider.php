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

namespace Qunity\UnitTest\Component\DataManager\AbstractContainer;

use ArrayIterator;
use Qunity\Component\DataManager\ContainerFactory;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\AbstractContainer
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
                ContainerFactory::create()
            ], [
                new ArrayIterator(['key_1' => 'value_1', 'key_2' => 'value_2', 'key_3' => 'value_3']),
                ContainerFactory::create(['key_1' => 'value_1', 'key_2' => 'value_2', 'key_3' => 'value_3'])
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
                ['key/0/0', ContainerFactory::create(['value_2'])],
                ['key/0/0', ContainerFactory::create(['value_1', 'value_2'])]
            ], [
                ['key/0/0/key', 'value_error'],
                ['key/0/0', ContainerFactory::create(['key' => 'value'])],
                ['key/0/0', ContainerFactory::create(['key' => 'value'])]
            ], [
                ['key/0/0', ['key' => 'value_error']],
                ['key/0', [ContainerFactory::create(['key' => 'value'])]],
                ['key/0/0', ContainerFactory::create(['key' => 'value'])]
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
                        'key_3/key_31' => ContainerFactory::create([
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                        ])
                    ],
                    'flat' => [
                        'key_1' => 'value',
                        'key_2/key_21/0/0' => 'value_2100',
                        'key_2/key_21/0/1' => 'value_2101',
                        'key_3/key_31' => ContainerFactory::create([
                            ['value_3100', 'value_3101'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112']
                        ])
                    ],
                    'real' => [
                        'key_1' => 'value',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101']]],
                        'key_3' => [
                            'key_31' => ContainerFactory::create([
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
                        'key_3/key_31' => ContainerFactory::create([
                            ['value_3102'],
                            ['key_3' => 'value_3113']
                        ])
                    ],
                    'flat' => [
                        'key_1' => 'value_replace',
                        'key_2/key_21/0' => ['value_2100', 'value_2101', 'value_2102'],
                        'key_3/key_31' => ContainerFactory::create([
                            ['value_3100', 'value_3101', 'value_3102'],
                            ['key_1' => 'value_3111', 'key_2' => 'value_3112', 'key_3' => 'value_3113']
                        ])
                    ],
                    'real' => [
                        'key_1' => 'value_replace',
                        'key_2' => ['key_21' => [['value_2100', 'value_2101', 'value_2102']]],
                        'key_3' => [
                            'key_31' => ContainerFactory::create([
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
                            'key_31' => ContainerFactory::create([
                                [1 => 'value_3101'],
                                ['key_1' => 'value_3111']
                            ])
                        ]
                    ]
                ]
            ],
        ];
    }
}
