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

namespace Qunity\UnitTest\Component\DataManager\Helper\Identifier;

use InvalidArgumentException;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Helper\Identifier
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
    public function providerClearId(): array
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
    public function providerClearIds(): array
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
    public function providerGetKeysById(): array
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
    public function providerGetIdByIds(): array
    {
        return [
            ['', []],
            ['0', [0]],
            ['0/0/0/0', [0, 0, 0, 0]],
            ['key/key/0/0', [0, 0, 'key', 'key']],
            ['key/key/0/0', ['', ' _0_ ', ' _0_ ', '', '/KEY/key/', '']],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGetMethodById(): array
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
    public function providerGetIdByMethod(): array
    {
        return [
            ['', '', 3],
            ['item_key_value', 'getItemKeyValue', 3],
            ['level_array/0/0/item_key_value', 'setLevelArray__0_0__itemKeyValue', 3],
            ['level_array/0/0/item_key_value', ' //_levelArray__0_0__itemKeyValue //_', null],
        ];
    }
}
