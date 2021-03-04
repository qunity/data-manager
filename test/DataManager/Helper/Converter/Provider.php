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

namespace Qunity\UnitTest\Component\DataManager\Helper\Converter;

use InvalidArgumentException;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Helper\Converter
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
            [true, 'key/0/key/0', null],
            [true, 'key/0/key/0', false],
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
                'Argument must be of the form "name", given argument is be "path": key/0',
                'key/0',
                true
            ], [
                InvalidArgumentException::class,
                'Argument must be of the form "path", given argument is be "name": 0',
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
            ['key/0/key/0', 'key/0/key/0'],
            ['key/0/key/0', '_//_key/0/ _/key/0_//_'],
            ['key/0/key/0', '_//_key/0/_ /key/0_//_'],
            ['key/0/key/0', ' _//_KEY/_0/_ _/KEY_/0_//_ '],
        ];
    }

    /**
     * @return array[]
     */
    public function providerClearKeys(): array
    {
        return [
            [[], []],
            [['key', '0', 'key', '0'], ['key', '0', 'key', 0]],
            [['key', '0/key', '0'], ['_//_key/', '0/ _/key/', '0_//_']],
            [['key', '0', 'key/0'], ['_//_key', '/0/_ /', 'key/0_//_']],
            [['key/0', 'key/0'], ['', ' _//_KEY/0', '/_ _/', 'KEY/0_//_ ', '']],
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
            [['0', 'key', '0', 'key'], 'key/0/key/0'],
            [['0', 'key', '0', 'key'], '_//_key/0/ _/key/0_//_'],
            [['0', 'key', '0', 'key'], ' _//_KEY/0/_ _/KEY/0_//_ '],
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
            ['key/0/key/0', ['0', 'key', '0', 'key']],
            ['key/0/key/0', ['', 0, 'key', 0, 'key', '']],
            ['key/0/key/0', ['', '_0_', 'key', '', '_0', 'key', '']],
            ['key/0/key/0', ['', '', '_0_', 'key', '', '0_', 'key', '', '']],
            ['key/0/key/0', ['', '', '_0_', 'KEY', '', '/KEY/ _0 ', '', '']],
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
            ['setLevelArray1_itemKeyValue', '/_/__/_level__array_1__//__item_key_value_/_/__/', 'set'],
            ['levelArray1_levelArray2_itemKeyValue', '__/_level__array_1_/_level_array2_/_item_key_value_/__', null],
            ['hasLevelArrayData_itemKeyValue', 'level_ARRAY_data/ / /item_KEY_value', 'has'],
            ['levelArrayData_level_itemKeyValue', '__/_//_level_ARRAY_data_///_level_/item_KEY_value/_/__//_', null],
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
            ['level_array_data/0/level/item_key_value', 'setLevelArrayData__0_level_itemKeyValue', 3],
            ['level_array_data/0/level/item_key_value', ' __levelArrayData__0_level_itemKeyValue__ ', null],
        ];
    }
}
