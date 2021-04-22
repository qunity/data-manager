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
     * @var array<int,array>
     */
    protected static array $cache = [];

    /**
     * Get identifier by identifiers
     *
     * @param array<int|string> $ids
     * @return string
     */
    public static function getIdByIds(array $ids): string
    {
        $result = '';
        if (($ids = self::clearIds($ids)) != []) {
            static $cacheId;
            if (($result = self::getCache($cacheId, $itemId = self::getArrayId($ids))) === null) {
                array_walk($ids, function (int|string &$id): void {
                    $id = self::getKeysById($id);
                });
                /** @var string[][] $ids */
                $result = implode(DataManagerInterface::DELIMITER_PATH, array_reverse(array_merge(...$ids)));
                self::setCache($cacheId, $itemId, $result);
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
            static $cacheId;
            if (($result = self::getCache($cacheId, $itemId = self::getArrayId($ids))) === null) {
                array_walk($ids, function (int|string &$id): void {
                    $id = self::clearId($id);
                });
                $result = array_values(array_diff($ids, ['']));
                self::setCache($cacheId, $itemId, $result);
            }
        }
        return $result;
    }

    /**
     * Get value from class cache
     *
     * @param int|null $cacheId
     * @param int|string $itemId
     *
     * @return mixed
     */
    protected static function getCache(?int $cacheId, int|string $itemId): mixed
    {
        if ($cacheId !== null && isset(self::$cache[$cacheId][$itemId])) {
            return self::$cache[$cacheId][$itemId];
        }
        return null;
    }

    /**
     * Set value to class cache
     *
     * @param int|null $cacheId
     * @param int|string $itemId
     * @param mixed $value
     */
    protected static function setCache(?int &$cacheId, int|string $itemId, mixed $value): void
    {
        if ($cacheId === null) {
            $cacheId = array_key_last(self::$cache) + 1;
        }
        if (!isset(self::$cache[$cacheId][$itemId])) {
            self::$cache[$cacheId][$itemId] = $value;
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
            static $cacheId;
            if (($result = self::getCache($cacheId, $id)) === null) {
                $result = preg_replace([
                    '%' . DataManagerInterface::DELIMITER_KEY . '{2,}%',
                    '%[^a-z0-9]*' . DataManagerInterface::DELIMITER_PATH . '+[^a-z0-9]*%'
                ], [
                    DataManagerInterface::DELIMITER_KEY,
                    DataManagerInterface::DELIMITER_PATH
                ], strtolower(trim(
                    (string)$id,
                    ' ' . DataManagerInterface::DELIMITER_KEY . DataManagerInterface::DELIMITER_PATH
                )));
                self::setCache($cacheId, $id, $result);
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
            static $cacheId;
            if (($result = self::getCache($cacheId, $id)) === null) {
                $result = array_reverse(explode(DataManagerInterface::DELIMITER_PATH, $id));
                self::setCache($cacheId, $id, $result);
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
            static $cacheId;
            if (($result = self::getCache($cacheId, $id)) === null) {
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
                self::setCache($cacheId, $id, $result);
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
            static $cacheId;
            if (($result = self::getCache($cacheId, $method)) === null) {
                $result = self::clearId((string)preg_replace(
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
     * Check if identifier is path
     *
     * @param int|string $id
     * @param bool|null $throw
     *
     * @return bool
     */
    public static function isPath(int|string $id, bool $throw = null): bool
    {
        $result = str_contains((string)$id, DataManagerInterface::DELIMITER_PATH);
        if ($throw !== null && $throw == $result) {
            throw new InvalidArgumentException(sprintf(
                "Argument must be of the form '%s', given argument is be '%s': %s",
                ...($throw ? ['name', 'path', $id] : ['path', 'name', $id])
            ));
        }
        return $result;
    }
}
