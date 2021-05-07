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
                'Argument must be of the form \'key\', given argument is be \'path\': key/0',
                'key/0',
                true,
            ], [
                InvalidArgumentException::class,
                'Argument must be of the form \'path\', given argument is be \'key\': 0',
                0,
                false,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGetKeys(): array
    {
        return [
            [[], ''],
            [['0'], 0],
            [['0', '0', '0', '0'], '0/0/0/0'],
            [['0', '0', 'key', 'key'], 'key/key/0/0'],
        ];
    }

    /**
     * @return array[]
     */
    public function providerGetUnderscore(): array
    {
        return [
            ['', '', 3],
            ['item_key_value', 'getItemKeyValue', 3],
            ['level_array/0/0/item_key_value', 'setLevelArray_0_0_itemKeyValue', 3],
            ['level_array/0/0/item_key_value', 'LevelArray_0_0_itemKeyValue', null],
        ];
    }
}
