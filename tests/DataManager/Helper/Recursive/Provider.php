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

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
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
                ['value'],
            ], [
                ['key' => 'value'],
                ['key'],
                'value',
                [],
            ], [
                ['key' => ['value'], 2 => 'value'],
                [0, 'key'],
                'value',
                ['key' => ['value_error'], 2 => 'value'],
            ], [
                ['key' => ['value']],
                [0, 'key'],
                'value',
                ['key' => ['value_error']],
            ], [
                ['key' => ['value_1', 'value_2']],
                [0, 'key'],
                'value_1',
                ['key' => ['value_error', 'value_2']],
            ], [
                ['key' => ['key' => ['key' => 'value']]],
                ['key', 'key', 'key'],
                'value',
                ['key' => ['key' => 'value_error']],
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
                ['value'],
            ], [
                ['key' => [1 => 'value']],
                [1, 'key'],
                'value',
                [],
            ], [
                ['key' => ['value'], 2 => 'value_2'],
                ['key'],
                ['value'],
                ['key' => [], 2 => 'value_2'],
            ], [
                ['key' => 'value', 2 => 'value_2'],
                ['key'],
                'value',
                ['key' => 'value_error', 2 => 'value_2'],
            ], [
                ['key' => ['value_1', 'value_2'], 2 => 'value_2'],
                ['key'],
                'value_2',
                ['key' => ['value_1'], 2 => 'value_2'],
            ], [
                ['key' => [['key' => 'value']]],
                ['key', 0, 'key'],
                'value',
                ['key' => [['key' => 'value_error']]],
            ], [
                ['key' => [['key' => ['value_1', 'value_2']]]],
                ['key', 0, 'key'],
                'value_2',
                ['key' => [['key' => ['value_1']]]],
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
                ['value'],
            ], [
                [],
                [0],
                ['value'],
            ], [
                ['key' => ['value']],
                [1, 'key'],
                ['key' => ['value', 'value_error']],
            ], [
                ['key' => ['value']],
                [1, 'key'],
                ['key' => ['value', 'value_error']],
            ], [
                ['key' => ['value_1', 'value_2']],
                [5, 'key'],
                ['key' => ['value_1', 'value_2']],
            ],
        ];
    }
}
