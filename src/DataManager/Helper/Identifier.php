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
 */
class Identifier
{
    /**
     * Underscore form
     * @var array<string>
     */
    protected static array $underscore = [];

    /**
     * Identifier keys
     * @var array<int|string,array>
     */
    protected static array $keys = [];

    /**
     * Get keys by identifier
     * @SuppressWarnings(PHPMD.ShortVariable)
     *
     * @param int|string $id
     * @return array<int,string>
     */
    public static function getKeys(int|string $id): array
    {
        if ($id !== '') {
            if (isset(self::$keys[$id])) {
                return self::$keys[$id];
            } else {
                return self::$keys[$id] = array_reverse(
                    explode(DataManagerInterface::DELIMITER_PATH, (string)$id)
                );
            }
        }
        return [];
    }

    /**
     * Get underscore form by method name
     *
     * @param string $method
     * @param int $offset
     *
     * @return string
     */
    public static function getUnderscore(string $method, int $offset = 0): string
    {
        if ($method !== '') {
            if ($offset != 0) {
                $method = substr($method, $offset);
            }
            if (isset(self::$underscore[$method])) {
                return self::$underscore[$method];
            } else {
                return self::$underscore[$method] = (string)preg_replace([
                    '%' . DataManagerInterface::DELIMITER_KEY . '{2,}%',
                    '%[^a-z0-9]*' . DataManagerInterface::DELIMITER_PATH . '+[^a-z0-9]*%'
                ], [
                    DataManagerInterface::DELIMITER_KEY,
                    DataManagerInterface::DELIMITER_PATH
                ], strtolower(trim(
                    (string)preg_replace(
                        ['%' . DataManagerInterface::DELIMITER_KEY . '+%', '%([A-Z]|[0-9]+)%'],
                        [DataManagerInterface::DELIMITER_PATH, DataManagerInterface::DELIMITER_KEY . '\\1'],
                        $method
                    ),
                    DataManagerInterface::DELIMITER_KEY . DataManagerInterface::DELIMITER_PATH
                )));
            }
        }
        return '';
    }

    /**
     * Check if identifier is path
     * @SuppressWarnings(PHPMD.ShortVariable)
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
