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

namespace Qunity\UnitTest\Component\DataManager\Recursive;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Recursive
 */
trait Provider
{
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
                ['key' => new DataManager(['value'])],
                [0, 'key'],
                'value',
                ['key' => new DataManager([['value_error']])]
            ], [
                ['key' => new DataManager([['value_1', 'value_2']])],
                [0, 0, 'key'],
                'value_1',
                ['key' => new DataManager([['value_error', 'value_2']])]
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
                ['key' => new DataManager([['key' => 'value']])],
                ['key', 0, 'key'],
                'value',
                ['key' => new DataManager([['key' => 'value_error']])]
            ], [
                ['key' => new DataManager([['key' => ['value_1', 'value_2']]])],
                ['key', 0, 'key'],
                'value_2',
                ['key' => new DataManager([['key' => ['value_1']]])]
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
            ['value', ['key', 0, 'key'], ['key' => new DataManager([['key' => 'value']])], null],
            ['value', ['key', 0, 'key'], ['key' => new DataManager([['key' => 'value']])], 'default'],
            [null, ['key', 5, 'key'], ['key' => new DataManager([['key' => 'value']])], null],
            ['default', ['key', 5, 'key'], ['key' => new DataManager([['key' => 'value']])], 'default'],
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
            [true, ['key', 0, 'key'], ['key' => new DataManager([['key' => 'value']])]],
            [false, ['key', 5, 'key'], ['key' => new DataManager([['key' => 'value']])]],
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
                ['key' => new DataManager(['value'])],
                [1, 'key'],
                ['key' => new DataManager(['value', 'value_error'])]
            ], [
                ['key' => new DataManager(['value_1', 'value_2'])],
                [5, 'key'],
                ['key' => new DataManager(['value_1', 'value_2'])]
            ],
        ];
    }
}
