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

use LogicException;
use Qunity\Component\DataManager\ConfigurableInterface;
use Qunity\Component\DataManagerFactory;
use Qunity\Component\DataManagerInterface;

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
                    $data[$key]->set(Converter::getPathByKeys($keys), $value);
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
                    $data[$key]->add(Converter::getPathByKeys($keys), $value);
                }
            } elseif (isset($data[$key])) {
                $data[$key] = self::join($data[$key], $value);
            } else {
                $data[$key] = $value;
            }
        }
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
                    return $carry->add($item);
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
     * @param array<mixed,array> ...$items
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
                        return $data[$key]->get(Converter::getPathByKeys($keys), $default);
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
                        return $data[$key]->has(Converter::getPathByKeys($keys));
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
                        $data[$key]->del(Converter::getPathByKeys($keys));
                    }
                }
            } else {
                unset($data[$key]);
            }
        }
    }

    /**
     * Configure object
     * Works recursively only with classes implement the interface ConfigurableInterface
     *
     * @param callable $callback
     * @param array<int|string,mixed>|DataManagerInterface $config
     */
    public static function configure(callable $callback, array|DataManagerInterface $config): void
    {
        $objects = [];
        foreach ($config as $name => $item) {
            if (!isset($item['data'])) {
                $item['data'] = [];
            }
            if (!isset($item['class'])) {
                $item['class'] = null;
            }
            $object = DataManagerFactory::create($item['data'], $item['class']);
            if (isset($item['config'])) {
                if (!($object instanceof ConfigurableInterface)) {
                    $class = $object::class;
                    $interface = ConfigurableInterface::class;
                    throw new LogicException("Class $class does not implement the interface $interface");
                }
                call_user_func([$object, 'configure'], $item['config']);
            }
            $objects[$name] = $object;
        }
        call_user_func($callback, $objects);
    }
}
