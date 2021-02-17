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
                ['key' => ['value'], 2 => 'value_2'],
                [0, 'key'],
                'value',
                ['key' => ['value_error'], 2 => 'value_2']
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
            ['default', [], ['value'], 'default'],
            ['value_1', [0, 'key'], ['key' => ['value_1', 'value_2']], 'default'],
            ['default', [5, 'key'], ['key' => ['value_1', 'value_2']], 'default'],
            ['value', ['key', 0, 'key'], ['key' => DataManagerFactory::create([['key' => 'value']])], 'default'],
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
