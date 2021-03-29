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

use InvalidArgumentException;
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
     * @param int|string $path
     * @return string[]
     */
    public static function getKeysByPath(int | string $path): array
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
     * @param int|string $path
     * @return string
     */
    public static function clearPath(int | string $path): string
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
     * @SuppressWarnings(PHPMD.ShortVariable)
     *
     * @param int|null $id
     * @param int|string $key
     *
     * @return mixed
     */
    protected static function getCache(?int $id, int | string $key): mixed
    {
        if ($id !== null && isset(self::$cache[$id][$key])) {
            return self::$cache[$id][$key];
        }
        return null;
    }

    /**
     * Set value to software cache
     * @SuppressWarnings(PHPMD.ShortVariable)
     *
     * @param int|null $id
     * @param int|string $key
     * @param mixed $value
     */
    protected static function setCache(?int &$id, int | string $key, mixed $value): void
    {
        if ($id === null) {
            $id = (int)array_key_last(self::$cache) + 1;
        }
        if (!isset(self::$cache[$id][$key])) {
            self::$cache[$id][$key] = $value;
        }
    }

    /**
     * Get path by keys
     *
     * @param int[]|string[] $keys
     * @return string
     */
    public static function getPathByKeys(array $keys): string
    {
        $result = '';
        if (($keys = self::clearKeys($keys)) != []) {
            static $cacheId;
            if (($result = self::getCache($cacheId, $keysId = self::getArrayId($keys))) === null) {
                array_walk($keys, function (int | string &$key): void {
                    $key = array_reverse(explode(DataManagerInterface::DELIMITER_PATH, (string)$key));
                });
                /** @var string[][] $keys */
                $result = implode(DataManagerInterface::DELIMITER_PATH, array_reverse(array_merge(...$keys)));
                self::setCache($cacheId, $keysId, $result);
            }
        }
        return $result;
    }

    /**
     * Clear the trash keys
     *
     * @param int[]|string[] $keys
     * @return string[]
     */
    public static function clearKeys(array $keys): array
    {
        $result = [];
        if ($keys != []) {
            static $cacheId;
            if (($result = self::getCache($cacheId, $keysId = self::getArrayId($keys))) === null) {
                array_walk($keys, function (int | string &$key): void {
                    $key = self::clearPath($key);
                });
                $result = array_values(array_diff($keys, ['']));
                self::setCache($cacheId, $keysId, $result);
            }
        }
        return $result;
    }

    /**
     * Get array identifier
     *
     * @param int[]|string[] $array
     * @return string
     */
    #[Pure] protected static function getArrayId(array $array): string
    {
        return implode(chr(1), $array);
    }

    /**
     * Get method name by path
     *
     * @param int|string $path
     * @param string $prefix
     *
     * @return string
     */
    public static function getMethodByPath(int | string $path, string $prefix = ''): string
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
                    (string)preg_replace_callback(
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
                $result = self::clearPath((string)preg_replace(
                    ['%' . DataManagerInterface::DELIMITER_KEY . '+%', '%([A-Z]|[0-9]+)%'],
                    [DataManagerInterface::DELIMITER_PATH, DataManagerInterface::DELIMITER_KEY . '\\1'],
                    $method
                ));
                self::setCache($cacheId, $method, $result);
            }
        }
        return $result;
    }

    /**
     * Check if value is path
     *
     * @param int|string $value
     * @param bool|null $throw
     *
     * @return bool
     */
    public static function isPath(int | string $value, bool $throw = null): bool
    {
        $result = str_contains((string)$value, DataManagerInterface::DELIMITER_PATH);
        if ($throw !== null && $throw == $result) {
            throw new InvalidArgumentException(sprintf(
                'Argument must be of the form "%s", given argument is be "%s": %s',
                ...($throw ? ['name', 'path', $value] : ['path', 'name', $value])
            ));
        }
        return $result;
    }
}
