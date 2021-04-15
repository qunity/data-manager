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

namespace Qunity\Component\DataManager;

use Qunity\Component\DataManagerInterface;

/**
 * Class Recursive
 * @package Qunity\Component\DataManager
 */
class Recursive
{
    /**
     * Set element recursively
     *
     * @param array<int|string> $keys
     * @param mixed $value
     * @param array<int|string,mixed>|DataManagerInterface $data
     */
    public static function set(array $keys, mixed $value, array|DataManagerInterface &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (!(isset($data[$key]) && (is_array($data[$key]) || $data[$key] instanceof DataManagerInterface))) {
                    $data[$key] = [];
                }
                if (is_array($data[$key])) {
                    self::set($keys, $value, $data[$key]);
                } elseif ($data[$key] instanceof DataManagerInterface) {
                    $data[$key]->set(Helper::getPathByKeys($keys), $value);
                }
            } else {
                $data[$key] = $value;
            }
        }
    }

    /**
     * Add element recursively
     *
     * @param array<int|string> $keys
     * @param mixed $value
     * @param array<int|string,mixed>|DataManagerInterface $data
     */
    public static function add(array $keys, mixed $value, array|DataManagerInterface &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (!(isset($data[$key]) && (is_array($data[$key]) || $data[$key] instanceof DataManagerInterface))) {
                    $data[$key] = [];
                }
                if (is_array($data[$key])) {
                    self::add($keys, $value, $data[$key]);
                } elseif ($data[$key] instanceof DataManagerInterface) {
                    $data[$key]->add(Helper::getPathByKeys($keys), $value);
                }
            } elseif (isset($data[$key])) {
                $data[$key] = Helper::join($data[$key], $value);
            } else {
                $data[$key] = $value;
            }
        }
    }

    /**
     * Get element recursively
     *
     * @param array<int|string> $keys
     * @param array<int|string,mixed>|DataManagerInterface $data
     * @param mixed|null $default
     *
     * @return mixed
     */
    public static function get(array $keys, array|DataManagerInterface $data, mixed $default = null): mixed
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    if (is_array($data[$key])) {
                        return self::get($keys, $data[$key], $default);
                    } elseif ($data[$key] instanceof DataManagerInterface) {
                        return $data[$key]->get(Helper::getPathByKeys($keys), $default);
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
     * @param array<int|string> $keys
     * @param array<int|string,mixed>|DataManagerInterface $data
     *
     * @return bool
     */
    public static function has(array $keys, array|DataManagerInterface $data): bool
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    if (is_array($data[$key])) {
                        return self::has($keys, $data[$key]);
                    } elseif ($data[$key] instanceof DataManagerInterface) {
                        return $data[$key]->has(Helper::getPathByKeys($keys));
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
     * @param array<int|string> $keys
     * @param array<int|string,mixed>|DataManagerInterface $data
     */
    public static function del(array $keys, array|DataManagerInterface &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    if (is_array($data[$key])) {
                        self::del($keys, $data[$key]);
                    } elseif ($data[$key] instanceof DataManagerInterface) {
                        $data[$key]->del(Helper::getPathByKeys($keys));
                    }
                }
            } else {
                unset($data[$key]);
            }
        }
    }
}
