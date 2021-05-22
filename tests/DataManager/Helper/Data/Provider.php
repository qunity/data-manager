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

namespace Qunity\UnitTest\Component\DataManager\Helper\Data;

/**
 * Trait Provider
 * @package Qunity\UnitTest\Component\DataManager\Helper\Data
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
            ],
            [
                ['key' => 'value'],
                ['key' => 'value'],
                ['key' => 'value'],
            ],
            [
                ['key_1' => 'value_1', 'key_2' => 'value_2'],
                ['key_1' => 'value_1'],
                ['key_2' => 'value_2'],
            ],
            [
                ['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value'],
                ['key_1' => 'value_error'],
                ['key_2' => 'value_error'],
                ['key_1' => 'value', 'key_2' => 'value', 'key_3' => 'value'],
            ],
            [
                ['value_1', 'value_2'],
                ['value_1'],
                'value_2',
            ],
            [
                ['key' => ['value_1', 'value_2', 'value_3']],
                ['key' => ['value_1']],
                ['key' => ['value_2']],
                ['key' => ['value_3']],
            ],
        ];
    }
}
