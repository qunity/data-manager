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

/**
 * Class Recursive
 * @package Qunity\Component\DataManager\Helper
 */
class Recursive
{
    /**
     * Set element recursively
     *
     * @param array<int|string> $keys
     * @param mixed $value
     * @param array<int|string,mixed> $data
     */
    public static function set(array $keys, mixed $value, array &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (!(isset($data[$key]) && is_array($data[$key]))) {
                    $data[$key] = [];
                }
                self::set($keys, $value, $data[$key]);
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
     * @param array<int|string,mixed> $data
     */
    public static function add(array $keys, mixed $value, array &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (!(isset($data[$key]) && is_array($data[$key]))) {
                    $data[$key] = [];
                }
                self::add($keys, $value, $data[$key]);
            } elseif (isset($data[$key])) {
                $data[$key] = Data::join($data[$key], $value);
            } else {
                $data[$key] = $value;
            }
        }
    }

    /**
     * Get element recursively
     *
     * @param array<int|string> $keys
     * @param array<int|string,mixed> $data
     * @param mixed|null $default
     *
     * @return mixed
     */
    public static function get(array $keys, array $data, mixed $default = null): mixed
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    return self::get($keys, $data[$key], $default);
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
     * @param array<int|string,mixed> $data
     *
     * @return bool
     */
    public static function has(array $keys, array $data): bool
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    return self::has($keys, $data[$key]);
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
     * @param array<int|string,mixed> $data
     */
    public static function del(array $keys, array &$data): void
    {
        if (($key = array_pop($keys)) !== null) {
            if ($keys != []) {
                if (isset($data[$key])) {
                    self::del($keys, $data[$key]);
                }
            } else {
                unset($data[$key]);
            }
        }
    }
}
