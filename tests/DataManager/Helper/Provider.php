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

namespace Qunity\UnitTest\Component\DataManager\Helper;

use InvalidArgumentException;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Helper
 */
trait Provider
{
    /**
     * @return array[]
     */
    public function providerIsPath(): array
    {
        return [
            [false, '', null],
            [false, '', true],
            [false, 0, null],
            [false, 0, true],
            [false, 'key', null],
            [false, 'key', true],
            [true, '0/0/0/0', null],
            [true, '0/0/0/0', false],
            [true, 'key/key/0/0', null],
            [true, 'key/key/0/0', false],
        ];
    }

    /**
     * @return array[]
     */
    public function providerIsPathThrow(): array
    {
        return [
            [
                InvalidArgumentException::class,
                'Argument must be of the form \'name\', given argument is be \'path\': key/0',
                'key/0',
                true
            ], [
                InvalidArgumentException::class,
                'Argument must be of the form \'path\', given argument is be \'name\': 0',
                0,
                false
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerClearPath(): array
    {
        return [
            ['', ''],
            ['0', 0],
            ['0/0/0/0', '0/0/0/0'],
            ['key/key/0/0', ' _//_KEY//key//0//0_//_ '],
        ];
    }

    /**
     * @return array[]
     */
    public function providerClearKeys(): array
    {
        return [
            [[], []],
            [['key', 'key', '0', '0'], ['key', 'key', 0, 0]],
            [['key/key', '0/0'], ['', ' _//_KEY/key', '/_ _/', '0/0_//_ ', '']],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGetKeysByPath(): array
    {
        return [
            [[], ''],
            [['0'], 0],
            [['0', '0', '0', '0'], '0/0/0/0'],
            [['0', '0', 'key', 'key'], 'key/key/0/0'],
            [['0', '0', 'key', 'key'], ' _//_KEY/key//0/0_//_ '],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGetPathByKeys(): array
    {
        return [
            ['', []],
            ['0', [0]],
            ['0/0/0/0', [0, 0, 0, 0]],
            ['key/key/0/0', [0, 0, 'key', 'key']],
            ['key/key/0/0', ['', '_0_', '0', '', '/KEY/key/', '']],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGetMethodByPath(): array
    {
        return [
            ['', '', 'get'],
            ['getItemKeyValue', 'item_key_value', 'get'],
            ['setLevelArray0_itemKeyValue', ' //_level_array_0_//_item_key_value_// ', 'set'],
            ['levelArray0_levelArray0_itemKeyValue', ' //_level_ARRAY_0_//_level_ARRAY_0_//_item_key_value_// ', null],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGetPathByMethod(): array
    {
        return [
            ['', '', 3],
            ['item_key_value', 'getItemKeyValue', 3],
            ['level_array/0/0/item_key_value', 'setLevelArray__0_0__itemKeyValue', 3],
            ['level_array/0/0/item_key_value', ' //_levelArray__0_0__itemKeyValue //_', null],
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
                new DataManager(['key_1' => 'value_1', 'key_2' => 'value_2']),
                ['key_1' => 'value_1'],
                new DataManager(['key_2' => 'value_2']),
            ], [
                new DataManager(['key' => 'value']),
                ['key' => 'value_error'],
                new DataManager(['key' => 'value']),
            ], [
                new DataManager(['key_1' => 'value_1', 'key_2' => 'value_2']),
                new DataManager(['key_1' => 'value_1']),
                ['key_2' => 'value_2'],
            ], [
                new DataManager(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value']),
                ['key_1' => 'value_error'],
                new DataManager(['key_2' => 'value_error']),
                new DataManager(['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value']),
            ], [
                'value',
                new DataManager(['value_error']),
                'value',
            ], [
                ['key' => ['value_1', 'value_2', 'value_3']],
                ['key' => ['value_1']],
                ['key' => ['value_2']],
                ['key' => ['value_3']],
            ], [
                ['key' => new DataManager(['value_1', 'value_2', 'value_3'])],
                ['key' => ['value_1']],
                ['key' => new DataManager(['value_2'])],
                ['key' => ['value_3']],
            ],
        ];
    }
}
