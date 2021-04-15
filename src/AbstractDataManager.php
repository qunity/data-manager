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

namespace Qunity\Component;

use ArrayIterator;
use BadMethodCallException;
use Qunity\Component\DataManager\Helper;
use Qunity\Component\DataManager\Recursive;
use Traversable;

/**
 * Class AbstractDataManager
 * @package Qunity\Component
 */
abstract class AbstractDataManager implements DataManagerInterface
{
    /**
     * Object recursive status
     */
    public const RECURSIVE = true;

    /**
     * Object data
     * @var array<int|string,mixed>
     */
    protected array $data = [];

    /**
     * AbstractDataManager constructor
     * @param array<int|string,mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    /**
     * @inheritDoc
     */
    public function set(array|int|string $path, mixed $value = null): static
    {
        if (is_array($path)) {
            $this->data = [];
            foreach ($path as $itemPath => $itemValue) {
                $this->set($itemPath, $itemValue);
            }
        } elseif ($path != '') {
            if (self::RECURSIVE && Helper::isPath($path)) { // @phpstan-ignore-line
                Recursive::set(Helper::getKeysByPath($path), $value, $this->data);
            } else {
                $this->data[$path] = $value;
            }
        }
        return $this;
    }

    /**
     * Call not existing methods
     *
     * @param string $method
     * @param array<int,mixed> $args
     *
     * @return mixed
     */
    public function __call(string $method, array $args): mixed
    {
        $callback = [$this, substr($method, 0, 3)];
        if (is_callable($callback) && method_exists(...$callback)) {
            return call_user_func($callback, Helper::getPathByMethod($method, 3), ...$args);
        }
        $class = $this::class;
        throw new BadMethodCallException("Call to invalid method $class::$method");
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function get(array|int|string $path = null, mixed $default = null): mixed
    {
        if ($path === null) {
            return $this->data;
        } elseif (is_array($path)) {
            $data = [];
            foreach ($path as $item) {
                if (is_array($item)) {
                    if (!isset($item['default'])) {
                        $item['default'] = $default;
                    }
                    list('path' => $itemPath, 'default' => $itemDefault) = $item;
                } else {
                    list('path' => $itemPath, 'default' => $itemDefault) = ['path' => $item, 'default' => $default];
                }
                $value = $this->get($itemPath, $itemDefault);
                if (self::RECURSIVE && Helper::isPath($itemPath)) { // @phpstan-ignore-line
                    Recursive::set(Helper::getKeysByPath($itemPath), $value, $data);
                } else {
                    $data[$itemPath] = $value;
                }
            }
            return $data;
        } elseif ($path != '') {
            if (self::RECURSIVE && Helper::isPath($path)) { // @phpstan-ignore-line
                return Recursive::get(Helper::getKeysByPath($path), $this->data, $default);
            } elseif (isset($this->data[$path])) {
                return $this->data[$path];
            }
        }
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function has(array|int|string $path = null): bool
    {
        if ($path === null) {
            return (bool)$this->data;
        } elseif (is_array($path)) {
            foreach ($path as $itemPath) {
                if (!$this->has($itemPath)) {
                    return false;
                }
            }
            return (bool)$path;
        } elseif ($path != '') {
            if (self::RECURSIVE && Helper::isPath($path)) { // @phpstan-ignore-line
                return Recursive::has(Helper::getKeysByPath($path), $this->data);
            } else {
                return isset($this->data[$path]);
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->del($offset);
    }

    /**
     * @inheritDoc
     */
    public function del(array|int|string $path = null): static
    {
        if ($path === null) {
            $this->data = [];
        } elseif (is_array($path)) {
            foreach ($path as $itemPath) {
                $this->del($itemPath);
            }
        } elseif ($path != '') {
            if (self::RECURSIVE && Helper::isPath($path)) { // @phpstan-ignore-line
                Recursive::del(Helper::getKeysByPath($path), $this->data);
            } else {
                unset($this->data[$path]);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->get());
    }

    /**
     * @inheritDoc
     */
    public function add(array|int|string $path, mixed $value = null): static
    {
        if (is_array($path)) {
            foreach ($path as $itemPath => $itemValue) {
                $this->add($itemPath, $itemValue);
            }
        } elseif ($path != '') {
            if (self::RECURSIVE && Helper::isPath($path)) { // @phpstan-ignore-line
                Recursive::add(Helper::getKeysByPath($path), $value, $this->data);
            } elseif (isset($this->data[$path])) {
                $this->data[$path] = Helper::join($this->data[$path], $value);
            } else {
                $this->data[$path] = $value;
            }
        }
        return $this;
    }
}
