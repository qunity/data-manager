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
use Qunity\Component\DataManager\Helper\Data;
use Qunity\Component\DataManager\Helper\Identifier;
use Qunity\Component\DataManager\Helper\Recursive;
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
    protected const RECURSIVE = true;

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
    public function set(array|int|string $id, mixed $value = null): static
    {
        if (is_array($id)) {
            $this->data = [];
            foreach ($id as $itemId => $itemValue) {
                $this->set($itemId, $itemValue);
            }
        } elseif ($id != '') {
            if (static::RECURSIVE && Identifier::isPath($id)) {
                Recursive::set(Identifier::getKeysByPath($id), $value, $this->data);
            } else {
                $this->data[$id] = $value;
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
            return call_user_func($callback, Identifier::getPathByMethod($method, 3), ...$args);
        }
        $class = $this::class;
        throw new BadMethodCallException("Call to invalid method: $class::$method");
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
    public function get(array|int|string $id = null, mixed $default = null): mixed
    {
        if ($id === null) {
            return $this->data;
        } elseif (is_array($id)) {
            $data = [];
            foreach ($id as $item) {
                if (is_array($item)) {
                    if (!isset($item['default'])) {
                        $item['default'] = $default;
                    }
                    list('id' => $itemId, 'default' => $itemDefault) = $item;
                } else {
                    list('id' => $itemId, 'default' => $itemDefault) = ['id' => $item, 'default' => $default];
                }
                $value = $this->get($itemId, $itemDefault);
                if (static::RECURSIVE && Identifier::isPath($itemId)) {
                    Recursive::set(Identifier::getKeysByPath($itemId), $value, $data);
                } else {
                    $data[$itemId] = $value;
                }
            }
            return $data;
        } elseif ($id != '') {
            if (static::RECURSIVE && Identifier::isPath($id)) {
                return Recursive::get(Identifier::getKeysByPath($id), $this->data, $default);
            } elseif (isset($this->data[$id])) {
                return $this->data[$id];
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
    public function has(array|int|string $id = null): bool
    {
        if ($id === null) {
            return (bool)$this->data;
        } elseif (is_array($id)) {
            foreach ($id as $itemId) {
                if (!$this->has($itemId)) {
                    return false;
                }
            }
            return (bool)$id;
        } elseif ($id != '') {
            if (static::RECURSIVE && Identifier::isPath($id)) {
                return Recursive::has(Identifier::getKeysByPath($id), $this->data);
            } else {
                return isset($this->data[$id]);
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
    public function del(array|int|string $id = null): static
    {
        if ($id === null) {
            $this->data = [];
        } elseif (is_array($id)) {
            foreach ($id as $itemId) {
                $this->del($itemId);
            }
        } elseif ($id != '') {
            if (static::RECURSIVE && Identifier::isPath($id)) {
                Recursive::del(Identifier::getKeysByPath($id), $this->data);
            } else {
                unset($this->data[$id]);
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
    public function add(array|int|string $id, mixed $value = null): static
    {
        if (is_array($id)) {
            foreach ($id as $itemId => $itemValue) {
                $this->add($itemId, $itemValue);
            }
        } elseif ($id != '') {
            if (static::RECURSIVE && Identifier::isPath($id)) {
                Recursive::add(Identifier::getKeysByPath($id), $value, $this->data);
            } elseif (isset($this->data[$id])) {
                $this->data[$id] = Data::join($this->data[$id], $value);
            } else {
                $this->data[$id] = $value;
            }
        }
        return $this;
    }
}
