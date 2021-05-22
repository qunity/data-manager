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
 * Class Callback
 * @package Qunity\Component\DataManager\Helper
 */
class Callback
{
    /**
     * Callbacks list
     * @var array<string,array<callable>>
     */
    protected static array $callbacks = [];

    /**
     * Get callback function "is_array"
     *
     * @param bool $empty
     * @return callable
     */
    public static function getIsArray(bool $empty = true): callable
    {
        if (isset(self::$callbacks['is_array'][$empty])) {
            return self::$callbacks['is_array'][$empty];
        }
        return self::$callbacks['is_array'][$empty] = $empty
            ? fn($value) => !empty($value) && is_array($value)
            : fn($value) => is_array($value);
    }

    /**
     * Get callback function "is_string"
     *
     * @param bool $empty
     * @return callable
     */
    public static function getIsString(bool $empty = true): callable
    {
        if (isset(self::$callbacks['is_string'][$empty])) {
            return self::$callbacks['is_string'][$empty];
        }
        return self::$callbacks['is_string'][$empty] = $empty
            ? fn($value) => !empty($value) && is_string($value)
            : fn($value) => is_string($value);
    }

    /**
     * Get callback function "is_scalar"
     *
     * @param bool $empty
     * @return callable
     */
    public static function getIsScalar(bool $empty = true): callable
    {
        if (isset(self::$callbacks['is_scalar'][$empty])) {
            return self::$callbacks['is_scalar'][$empty];
        }
        return self::$callbacks['is_scalar'][$empty] = $empty
            ? fn($value) => !empty($value) && is_scalar($value)
            : fn($value) => is_scalar($value);
    }

    /**
     * Get callback function "is_true"
     * @return callable
     */
    public static function getIsTrue(): callable
    {
        if (isset(self::$callbacks['is_true'][0])) {
            return self::$callbacks['is_true'][0];
        }
        return self::$callbacks['is_true'][0] = fn($value) => $value === true;
    }

    /**
     * Get callback function "is_false"
     * @return callable
     */
    public static function getIsFalse(): callable
    {
        if (isset(self::$callbacks['is_false'][0])) {
            return self::$callbacks['is_false'][0];
        }
        return self::$callbacks['is_false'][0] = fn($value) => $value === false;
    }

    /**
     * Get callback function "is_null"
     * @return callable
     */
    public static function getIsNull(): callable
    {
        if (isset(self::$callbacks['is_null'][0])) {
            return self::$callbacks['is_null'][0];
        }
        return self::$callbacks['is_null'][0] = fn($value) => $value === null;
    }
}
