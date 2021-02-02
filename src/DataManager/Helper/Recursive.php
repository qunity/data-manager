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

use Qunity\Component\DataManager\ContainerInterface;

/**
 * Class Recursive
 * @package Qunity\Component\DataManager\Helper
 */
class Recursive
{
    /**
     * Set element recursively
     *
     * @param array $keys
     * @param mixed $value
     * @param ContainerInterface|array $data
     *
     * @return void
     */
    public static function set(array $keys, mixed $value, ContainerInterface|array &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (!(isset($data[$key]) && (is_array($data[$key]) || $data[$key] instanceof ContainerInterface))) {
                    $data[$key] = [];
                }
                if (is_array($data[$key])) {
                    self::set($keys, $value, $data[$key]);
                } elseif ($data[$key] instanceof ContainerInterface) {
                    $data[$key]->setElement(Converter::getPathByKeys($keys), $value);
                }
            } else {
                $data[$key] = $value;
            }
        }
    }

    /**
     * Add element recursively
     *
     * @param array $keys
     * @param mixed $value
     * @param ContainerInterface|array $data
     *
     * @return void
     */
    public static function add(array $keys, mixed $value, ContainerInterface|array &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (!(isset($data[$key]) && (is_array($data[$key]) || $data[$key] instanceof ContainerInterface))) {
                    $data[$key] = [];
                }
                if (is_array($data[$key])) {
                    self::add($keys, $value, $data[$key]);
                } elseif ($data[$key] instanceof ContainerInterface) {
                    $data[$key]->addElement(Converter::getPathByKeys($keys), $value);
                }
            } elseif (isset($data[$key])) {
                $data[$key] = self::join($data[$key], $value);
            } else {
                $data[$key] = $value;
            }
        }
    }

    /**
     * Join data (arrays, containers, etc.)
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
                } elseif ($item instanceof ContainerInterface) {
                    return $item->setElements(self::join($carry, $item->getElements()));
                } else {
                    return array_merge($carry, (array)$item);
                }
            } elseif ($carry instanceof ContainerInterface) {
                if (is_array($item)) {
                    return $carry->addElements($item);
                } elseif ($item instanceof ContainerInterface) {
                    return $item->setElements(self::join($carry->getElements(), $item->getElements()));
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
     * @param array ...$items
     * @return array
     */
    protected static function joinArrays(array ...$items): array
    {
        return array_reduce($items, function (?array $carry, array $item): array {
            if ($carry !== null) {
                foreach ($item as $key => $value) {
                    if (isset($carry[$key]) && is_array($value)) {
                        $carry[$key] = self::join($carry[$key], $value);
                    } elseif (isset($carry[$key]) && $value instanceof ContainerInterface) {
                        $carry[$key] = $value->setElements(self::join($carry[$key], $value->getElements()));
                    } elseif (is_numeric($key)) {
                        $carry = array_merge($carry, (array)$value);
                    } else {
                        $carry[$key] = $value;
                    }
                }
                return $carry;
            } else {
                return $item;
            }
        });
    }

    /**
     * Get element recursively
     *
     * @param array $keys
     * @param ContainerInterface|array $data
     * @param mixed $default
     *
     * @return mixed
     */
    public static function get(array $keys, ContainerInterface|array $data, mixed $default = null): mixed
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    if (is_array($data[$key])) {
                        return self::get($keys, $data[$key], $default);
                    } elseif ($data[$key] instanceof ContainerInterface) {
                        return $data[$key]->getElement(Converter::getPathByKeys($keys), $default);
                    }
                }
            } elseif (isset($data[$key])) {
                return $data[$key];
            }
        }
        return $default;
    }

    /**
     * Check existence element recursively
     *
     * @param array $keys
     * @param ContainerInterface|array $data
     *
     * @return bool
     */
    public static function has(array $keys, ContainerInterface|array $data): bool
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    if (is_array($data[$key])) {
                        return self::has($keys, $data[$key]);
                    } elseif ($data[$key] instanceof ContainerInterface) {
                        return $data[$key]->hasElement(Converter::getPathByKeys($keys));
                    }
                }
            } else {
                return isset($data[$key]);
            }
        }
        return false;
    }

    /**
     * Remove element recursively
     *
     * @param array $keys
     * @param ContainerInterface|array $data
     *
     * @return void
     */
    public static function del(array $keys, ContainerInterface|array &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    if (is_array($data[$key])) {
                        self::del($keys, $data[$key]);
                    } elseif ($data[$key] instanceof ContainerInterface) {
                        $data[$key]->delElement(Converter::getPathByKeys($keys));
                    }
                }
            } else {
                unset($data[$key]);
            }
        }
    }
}
