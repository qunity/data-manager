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

use JetBrains\PhpStorm\Pure;
use Qunity\Component\DataManagerInterface;

/**
 * Class Converter
 * @package Qunity\Component\DataManager\Helper
 */
class Converter
{
    /**
     * Software cache
     * @var array[]
     */
    protected static array $cache = [];

    /**
     * Get keys by path
     *
     * @param string|int $path
     * @return array
     */
    public static function getKeysByPath(string | int $path): array
    {
        $result = [];
        if (($path = self::clearPath($path)) != '') {
            static $cacheId;
            if (($result = self::getCache($cacheId, $path)) === null) {
                $result = array_reverse(explode(DataManagerInterface::DELIMITER_PATH, $path));
                self::setCache($cacheId, $path, $result);
            }
        }
        return $result;
    }

    /**
     * Clear the trash path
     *
     * @param string|int $path
     * @return string
     */
    protected static function clearPath(string | int $path): string
    {
        $result = '';
        if ($path != '') {
            static $cacheId;
            if (($result = self::getCache($cacheId, $path)) === null) {
                $result = preg_replace([
                    '%' . DataManagerInterface::DELIMITER_KEY . '{2,}%',
                    '%[^a-z0-9]*' . DataManagerInterface::DELIMITER_PATH . '+[^a-z0-9]*%'
                ], [
                    DataManagerInterface::DELIMITER_KEY,
                    DataManagerInterface::DELIMITER_PATH
                ], strtolower(trim(
                    (string)$path,
                    ' ' . DataManagerInterface::DELIMITER_KEY . DataManagerInterface::DELIMITER_PATH
                )));
                self::setCache($cacheId, $path, $result);
            }
        }
        return $result;
    }

    /**
     * Get value from software cache
     *
     * @param int|null $id
     * @param string|int $key
     *
     * @return mixed
     */
    protected static function getCache(?int $id, string | int $key): mixed
    {
        if ($id !== null && isset(self::$cache[$id][$key])) {
            return self::$cache[$id][$key];
        }
        return null;
    }

    /**
     * Set value to software cache
     *
     * @param int|null $id
     * @param string|int $key
     * @param mixed $value
     */
    protected static function setCache(?int &$id, string | int $key, mixed $value): void
    {
        if ($id === null) {
            $id = array_key_last(self::$cache) + 1;
        }
        if (!isset(self::$cache[$id][$key])) {
            self::$cache[$id][$key] = $value;
        }
    }

    /**
     * Get path by keys
     *
     * @param array $keys
     * @return string
     */
    public static function getPathByKeys(array $keys): string
    {
        $result = '';
        if (($keys = self::clearKeys($keys)) != []) {
            static $cacheId;
            if (($result = self::getCache($cacheId, $keysId = self::getArrayId($keys))) === null) {
                array_walk($keys, function (string | int &$key): void {
                    $key = explode(DataManagerInterface::DELIMITER_PATH, $key);
                });
                $result = implode(DataManagerInterface::DELIMITER_PATH, array_reverse(array_merge(...$keys)));
                self::setCache($cacheId, $keysId, $result);
            }
        }
        return $result;
    }

    /**
     * Clear the trash keys
     *
     * @param array $keys
     * @return array
     */
    protected static function clearKeys(array $keys): array
    {
        $result = [];
        if ($keys != []) {
            static $cacheId;
            if (($result = self::getCache($cacheId, $keysId = self::getArrayId($keys))) === null) {
                $keys = array_diff($keys, ['', null]);
                array_walk($keys, function (string | int &$key): void {
                    $key = self::clearPath($key);
                });
                $result = $keys;
                self::setCache($cacheId, $keysId, $result);
            }
        }
        return $result;
    }

    /**
     * Get array identifier
     *
     * @param array $array
     * @return string
     */
    #[Pure] protected static function getArrayId(array $array): string
    {
        return implode(chr(1), $array);
    }

    /**
     * Get method name by path
     *
     * @param string|int $path
     * @param string $prefix
     *
     * @return string
     */
    public static function getMethodByPath(string | int $path, string $prefix = ''): string
    {
        $result = '';
        if (($path = self::clearPath($path)) != '') {
            if ($prefix != '') {
                $path = $prefix . DataManagerInterface::DELIMITER_KEY . $path;
            }
            static $cacheId;
            if (($result = self::getCache($cacheId, $path)) === null) {
                $result = str_replace(
                    DataManagerInterface::DELIMITER_PATH,
                    DataManagerInterface::DELIMITER_KEY,
                    preg_replace_callback(
                        '%' . DataManagerInterface::DELIMITER_KEY . '[a-z0-9]%',
                        function (array $matches): string {
                            return strtoupper(substr(reset($matches), 1));
                        },
                        $path
                    )
                );
                self::setCache($cacheId, $path, $result);
            }
        }
        return $result;
    }

    /**
     * Get path by method name
     *
     * @param string $method
     * @param int $offset
     *
     * @return string
     */
    public static function getPathByMethod(string $method, int $offset = 0): string
    {
        $result = '';
        if ($method != '') {
            if ($offset != 0) {
                $method = substr($method, $offset);
            }
            static $cacheId;
            if (($result = self::getCache($cacheId, $method)) === null) {
                $result = self::clearPath(preg_replace(
                    ['%' . DataManagerInterface::DELIMITER_KEY . '+%', '%([A-Z]|[0-9]+)%'],
                    [DataManagerInterface::DELIMITER_PATH, DataManagerInterface::DELIMITER_KEY . "$1"],
                    $method
                ));
                self::setCache($cacheId, $method, $result);
            }
        }
        return $result;
    }

    /**
     * Check if string is path
     *
     * @param string|int $path
     * @return bool
     */
    public static function isPath(string | int $path): bool
    {
        $result = false;
        if (($path = self::clearPath($path)) != '') {
            static $cacheId;
            if (($result = self::getCache($cacheId, $path)) === null) {
                $result = str_contains($path, DataManagerInterface::DELIMITER_PATH);
                self::setCache($cacheId, $path, $result);
            }
        }
        return $result;
    }
}
