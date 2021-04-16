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

use InvalidArgumentException;
use Qunity\Component\DataManagerInterface;

/**
 * Class Helper
 * @package Qunity\Component\DataManager
 */
class Helper
{
    /**
     * Software cache
     * @var array<int|string,array>
     */
    protected static array $cache = [];

    /**
     * Get path by keys
     *
     * @param array<int|string> $keys
     * @return string
     */
    public static function getPathByKeys(array $keys): string
    {
        $result = '';
        if (($keys = self::clearKeys($keys)) != []) {
            if (($result = self::getCache(__FUNCTION__, $keysId = self::getArrayId($keys))) === null) {
                array_walk($keys, function (int|string &$key): void {
                    $key = self::getKeysByPath($key);
                });
                /** @var string[][] $keys */
                $result = implode(DataManagerInterface::DELIMITER_PATH, array_reverse(array_merge(...$keys)));
                self::setCache(__FUNCTION__, $keysId, $result);
            }
        }
        return $result;
    }

    /**
     * Clear keys of trash
     *
     * @param array<int|string> $keys
     * @return array<int,string>
     */
    public static function clearKeys(array $keys): array
    {
        $result = [];
        if ($keys != []) {
            if (($result = self::getCache(__FUNCTION__, $keysId = self::getArrayId($keys))) === null) {
                array_walk($keys, function (int|string &$key): void {
                    $key = self::clearPath($key);
                });
                $result = array_values(array_diff($keys, ['']));
                self::setCache(__FUNCTION__, $keysId, $result);
            }
        }
        return $result;
    }

    /**
     * Get value from software cache
     *
     * @param int|string $code
     * @param int|string $key
     *
     * @return mixed
     */
    protected static function getCache(int|string $code, int|string $key): mixed
    {
        if (isset(self::$cache[$code][$key])) {
            return self::$cache[$code][$key];
        }
        return null;
    }

    /**
     * Set value to software cache
     *
     * @param int|string $code
     * @param int|string $key
     * @param mixed $value
     */
    protected static function setCache(int|string $code, int|string $key, mixed $value): void
    {
        if (!isset(self::$cache[$code][$key])) {
            self::$cache[$code][$key] = $value;
        }
    }

    /**
     * Get array identifier
     *
     * @param array<int|string> $array
     * @return string
     */
    protected static function getArrayId(array $array): string
    {
        return implode(chr(1), $array);
    }

    /**
     * Clear path of trash
     *
     * @param int|string $path
     * @return string
     */
    public static function clearPath(int|string $path): string
    {
        $result = '';
        if ($path != '') {
            if (($result = self::getCache(__FUNCTION__, $path)) === null) {
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
                self::setCache(__FUNCTION__, $path, $result);
            }
        }
        return $result;
    }

    /**
     * Get keys by path
     *
     * @param int|string $path
     * @return array<int,string>
     */
    public static function getKeysByPath(int|string $path): array
    {
        $result = [];
        if (($path = self::clearPath($path)) != '') {
            if (($result = self::getCache(__FUNCTION__, $path)) === null) {
                $result = array_reverse(explode(DataManagerInterface::DELIMITER_PATH, $path));
                self::setCache(__FUNCTION__, $path, $result);
            }
        }
        return $result;
    }

    /**
     * Get method name by path
     *
     * @param int|string $path
     * @param string $prefix
     *
     * @return string
     */
    public static function getMethodByPath(int|string $path, string $prefix = ''): string
    {
        $result = '';
        if (($path = self::clearPath($path)) != '') {
            if ($prefix != '') {
                $path = $prefix . DataManagerInterface::DELIMITER_KEY . $path;
            }
            if (($result = self::getCache(__FUNCTION__, $path)) === null) {
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
                self::setCache(__FUNCTION__, $path, $result);
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
            if (($result = self::getCache(__FUNCTION__, $method)) === null) {
                $result = self::clearPath((string)preg_replace(
                    ['%' . DataManagerInterface::DELIMITER_KEY . '+%', '%([A-Z]|[0-9]+)%'],
                    [DataManagerInterface::DELIMITER_PATH, DataManagerInterface::DELIMITER_KEY . '\\1'],
                    $method
                ));
                self::setCache(__FUNCTION__, $method, $result);
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
    public static function isPath(int|string $value, bool $throw = null): bool
    {
        $result = str_contains((string)$value, DataManagerInterface::DELIMITER_PATH);
        if ($throw !== null && $throw == $result) {
            throw new InvalidArgumentException(sprintf(
                "Argument must be of the form '%s', given argument is be '%s': %s",
                ...($throw ? ['name', 'path', $value] : ['path', 'name', $value])
            ));
        }
        return $result;
    }

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
     * @param array<int|string,array> ...$items
     * @return array<int|string,mixed>
     */
    protected static function joinArrays(array ...$items): array
    {
        return (array)array_reduce($items, function (?array $carry, array $item): array {
            if ($carry !== null) {
                foreach ($item as $key => $value) {
                    if (isset($carry[$key]) && is_array($value)) {
                        $carry[$key] = self::join($carry[$key], $value);
                    } elseif (isset($carry[$key]) && $value instanceof DataManagerInterface) {
                        $carry[$key] = $value->set(self::join($carry[$key], $value->get()));
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
}
