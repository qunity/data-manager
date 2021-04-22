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

namespace Qunity\Component\DataManager\Helper;

use Qunity\Component\DataManagerInterface;

/**
 * Class Data
 * @package Qunity\Component\DataManager\Helper
 */
class Data
{
    /**
     * Join data (arrays, managers, etc.)
     *
     * @param mixed ...$items
     * @return mixed
     */
    public static function join(mixed ...$items): mixed
    {
        return array_reduce($items, function (mixed $carry, mixed $item): mixed {
            if (is_array($carry)) {
                if (is_array($item)) {
                    return self::joinArrays($carry, $item);
                } elseif ($item instanceof DataManagerInterface) {
                    return $item->set(self::joinArrays($carry, $item->get()));
                } else {
                    return array_merge($carry, (array)$item);
                }
            } elseif ($carry instanceof DataManagerInterface) {
                if (is_array($item)) {
                    return $carry->set(self::joinArrays($carry->get(), $item));
                } elseif ($item instanceof DataManagerInterface) {
                    return $item->set(self::joinArrays($carry->get(), $item->get()));
                } else {
                    return $item;
                }
            } else {
                return $item;
            }
        });
    }

    /**
     * Join data (only arrays)
     *
     * @param array<int|string,mixed> ...$items
     * @return array<int|string,mixed>
     */
    protected static function joinArrays(array ...$items): array
    {
        return (array)array_reduce($items, function (?array $carry, array $item): array {
            if ($carry !== null) {
                foreach ($item as $id => $value) {
                    if (isset($carry[$id]) && is_array($value)) {
                        $carry[$id] = self::join($carry[$id], $value);
                    } elseif (isset($carry[$id]) && $value instanceof DataManagerInterface) {
                        $carry[$id] = $value->set(self::join($carry[$id], $value->get()));
                    } elseif (is_numeric($id)) {
                        $carry = array_merge($carry, (array)$value);
                    } else {
                        $carry[$id] = $value;
                    }
                }
                return $carry;
            } else {
                return $item;
            }
        });
    }
}
