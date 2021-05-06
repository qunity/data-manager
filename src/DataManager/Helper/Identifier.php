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
use Qunity\Component\DataManagerInterface;

/**
 * Class Identifier
 * @package Qunity\Component\DataManager\Helper
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Identifier
{
    /**
     * Class cache
     * @var array<int|string,array>
     */
    protected static array $cache = [];

    /**
     * Get path by identifiers
     *
     * @param array<int|string> $ids
     * @return string
     */
    public static function getPathByIds(array $ids): string
    {
        $result = '';
        if (($ids = self::clearIds($ids)) != []) {
            if (($result = self::getCache(__FUNCTION__, $arrayId = self::getArrayId($ids))) === null) {
                array_walk($ids, function (int|string &$id): void {
                    $id = self::getKeysById($id);
                });
                /** @var string[][] $ids */
                $result = implode(DataManagerInterface::DELIMITER_PATH, array_reverse(array_merge(...$ids)));
                self::setCache(__FUNCTION__, $arrayId, $result);
            }
        }
        return $result;
    }

    /**
     * Clear identifiers of trash
     *
     * @param array<int|string> $ids
     * @return array<int,string>
     */
    public static function clearIds(array $ids): array
    {
        $result = [];
        if ($ids != []) {
            if (($result = self::getCache(__FUNCTION__, $arrayId = self::getArrayId($ids))) === null) {
                array_walk($ids, function (int|string &$id): void {
                    $id = self::clearId($id);
                });
                $result = array_values(array_diff($ids, ['']));
                self::setCache(__FUNCTION__, $arrayId, $result);
            }
        }
        return $result;
    }

    /**
     * Get value from class cache
     *
     * @param int|string $cacheId
     * @param int|string $valueId
     *
     * @return mixed
     */
    protected static function getCache(int|string $cacheId, int|string $valueId): mixed
    {
        if (isset(self::$cache[$cacheId][$valueId])) {
            return self::$cache[$cacheId][$valueId];
        }
        return null;
    }

    /**
     * Set value to class cache
     *
     * @param int|string $cacheId
     * @param int|string $valueId
     * @param mixed $value
     */
    protected static function setCache(int|string $cacheId, int|string $valueId, mixed $value): void
    {
        if (!isset(self::$cache[$cacheId][$valueId])) {
            self::$cache[$cacheId][$valueId] = $value;
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
     * Clear identifier of trash
     *
     * @param int|string $id
     * @return string
     */
    public static function clearId(int|string $id): string
    {
        $result = '';
        if ($id != '') {
            if (($result = self::getCache(__FUNCTION__, $id)) === null) {
                $result = trim(
                    (string)$id,
                    ' ' . DataManagerInterface::DELIMITER_KEY . DataManagerInterface::DELIMITER_PATH
                );
                self::setCache(__FUNCTION__, $id, $result);
            }
        }
        return $result;
    }

    /**
     * Get keys by identifier
     *
     * @param int|string $id
     * @return array<int,string>
     */
    public static function getKeysById(int|string $id): array
    {
        $result = [];
        if (($id = self::clearId($id)) != '') {
            if (($result = self::getCache(__FUNCTION__, $id)) === null) {
                $result = array_reverse(explode(DataManagerInterface::DELIMITER_PATH, $id));
                self::setCache(__FUNCTION__, $id, $result);
            }
        }
        return $result;
    }

    /**
     * Get method name by identifier
     *
     * @param int|string $id
     * @param string $prefix
     *
     * @return string
     */
    public static function getMethodById(int|string $id, string $prefix = ''): string
    {
        $result = '';
        if (($id = self::clearId($id)) != '') {
            if ($prefix != '') {
                $id = $prefix . DataManagerInterface::DELIMITER_KEY . $id;
            }
            if (($result = self::getCache(__FUNCTION__, $id)) === null) {
                $result = str_replace(
                    DataManagerInterface::DELIMITER_PATH,
                    DataManagerInterface::DELIMITER_KEY,
                    (string)preg_replace_callback(
                        '%' . DataManagerInterface::DELIMITER_KEY . '[a-z0-9]%',
                        function (array $matches): string {
                            return strtoupper(substr(reset($matches), 1));
                        },
                        $id
                    )
                );
                self::setCache(__FUNCTION__, $id, $result);
            }
        }
        return $result;
    }

    /**
     * Get identifier by method name
     *
     * @param string $method
     * @param int $offset
     *
     * @return string
     */
    public static function getIdByMethod(string $method, int $offset = 0): string
    {
        $result = '';
        if ($method != '') {
            if ($offset != 0) {
                $method = substr($method, $offset);
            }
            if (($result = self::getCache(__FUNCTION__, $method)) === null) {
                $result = self::clearId((string)preg_replace(
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
     * Check if identifier is path
     *
     * @param int|string $id
     * @param bool|null $throw
     *
     * @return bool
     */
    public static function isPath(int|string $id, bool|null $throw = null): bool
    {
        $result = str_contains((string)$id, DataManagerInterface::DELIMITER_PATH);
        if ($throw !== null && $throw == $result) {
            throw new InvalidArgumentException(sprintf(
                "Argument must be of the form '%s', given argument is be '%s': %s",
                ...($throw ? ['key', 'path', $id] : ['path', 'key', $id])
            ));
        }
        return $result;
    }
}
