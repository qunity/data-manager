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

use Qunity\Component\DataManager\ContainerFactory;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
 */
trait Provider
{
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
                ContainerFactory::create(['key_1' => 'value_1', 'key_2' => 'value_2']),
                ['key_1' => 'value_1'],
                ContainerFactory::create(['key_2' => 'value_2']),
            ], [
                ContainerFactory::create(['key' => 'value']),
                ['key' => 'value_error'],
                ContainerFactory::create(['key' => 'value']),
            ], [
                ContainerFactory::create(['key_1' => 'value_1', 'key_2' => 'value_2']),
                ContainerFactory::create(['key_1' => 'value_1']),
                ['key_2' => 'value_2'],
            ], [
                ContainerFactory::create(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value']),
                ['key_1' => 'value_1'],
                ContainerFactory::create(['key_2' => 'value_2']),
                ContainerFactory::create(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value']),
            ], [
                'value',
                ContainerFactory::create(['value_error']),
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
                ['key' => ['value'], 2 => 'value_2'],
                [0, 'key'],
                'value',
                ['key' => ['value_error'], 2 => 'value_2']
            ], [
                ['key' => ContainerFactory::create(['value'])],
                [0, 'key'],
                'value',
                ['key' => ContainerFactory::create([['value_error']])]
            ], [
                ['key' => ContainerFactory::create([['value_1', 'value_2']])],
                [0, 0, 'key'],
                'value_1',
                ['key' => ContainerFactory::create([['value_error', 'value_2']])]
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
                ['key' => ContainerFactory::create([['key' => 'value']])],
                ['key', 0, 'key'],
                'value',
                ['key' => ContainerFactory::create([['key' => 'value_error']])]
            ], [
                ['key' => ContainerFactory::create([['key' => ['value_1', 'value_2']]])],
                ['key', 0, 'key'],
                'value_2',
                ['key' => ContainerFactory::create([['key' => ['value_1']]])]
            ], [
                ['key' => ContainerFactory::create([['key' => null]])],
                ['key', 0, 'key'],
                null,
                ['key' => ContainerFactory::create([['key' => 'value']])]
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGet(): array
    {
        return [
            ['default', [], ['value'], 'default'],
            ['value_1', [0, 'key'], ['key' => ['value_1', 'value_2']], 'default'],
            ['default', [5, 'key'], ['key' => ['value_1', 'value_2']], 'default'],
            ['value', ['key', 0, 'key'], ['key' => ContainerFactory::create([['key' => 'value']])], 'default'],
            ['default', ['key', 5, 'key'], ['key' => ContainerFactory::create([['key' => 'value']])], 'default'],
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
            [true, ['key', 0, 'key'], ['key' => ContainerFactory::create([['key' => 'value']])]],
            [false, ['key', 5, 'key'], ['key' => ContainerFactory::create([['key' => 'value']])]],
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
                ['key' => ContainerFactory::create(['value'])],
                [1, 'key'],
                ['key' => ContainerFactory::create(['value', 'value_error'])]
            ], [
                ['key' => ContainerFactory::create(['value_1', 'value_2'])],
                [5, 'key'],
                ['key' => ContainerFactory::create(['value_1', 'value_2'])]
            ],
        ];
    }
}
