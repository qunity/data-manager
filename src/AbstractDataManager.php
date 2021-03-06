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

namespace Qunity;

use ArrayIterator;
use BadMethodCallException;
use Qunity\DataManager\Helper\Data;
use Qunity\DataManager\Helper\Identifier;
use Qunity\DataManager\Helper\Recursive;
use Traversable;

/**
 * Class AbstractDataManager
 * @package Qunity
 */
abstract class AbstractDataManager implements DataManagerInterface
{
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
            if (Identifier::isPath($id)) {
                Recursive::set(Identifier::getKeys($id), $value, $this->data);
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
    public function __call(string $method, array $args)
    {
        $class = $this::class;
        return match (substr($method, 0, 3)) {
            'try' => $this->try(Identifier::getUnderscore($method, 3), ...$args),
            'set' => $this->set(Identifier::getUnderscore($method, 3), ...$args),
            'add' => $this->add(Identifier::getUnderscore($method, 3), ...$args),
            'get' => $this->get(Identifier::getUnderscore($method, 3), ...$args),
            'has' => $this->has(Identifier::getUnderscore($method, 3)),
            'del' => $this->del(Identifier::getUnderscore($method, 3)),
            default => throw new BadMethodCallException("Call to invalid method $class::$method"),
        };
    }

    /**
     * @inheritDoc
     */
    public function try(array|int|string|null $id = null, callable|null $check = null): bool
    {
        if ($id === null) {
            return (bool)$this->data;
        } elseif (is_array($id)) {
            foreach ($id as $itemId) {
                if (!$this->try($itemId, $check)) {
                    return false;
                }
            }
            return (bool)$id;
        } elseif ($id != '') {
            if (Identifier::isPath($id)) {
                return Recursive::try(Identifier::getKeys($id), $this->data, $check);
            } elseif (key_exists($id, $this->data)) {
                if ($check !== null) {
                    return (bool)call_user_func($check, $this->data[$id], $id);
                }
                return true;
            }
        }
        return false;
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
            if (Identifier::isPath($id)) {
                Recursive::add(Identifier::getKeys($id), $value, $this->data);
            } elseif (isset($this->data[$id])) {
                $this->data[$id] = Data::join($this->data[$id], $value);
            } else {
                $this->data[$id] = $value;
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(array|int|string|null $id = null, mixed $default = null): mixed
    {
        if ($id === null) {
            return $this->data;
        } elseif (is_array($id)) {
            $data = [];
            foreach ($id as $item) {
                if (is_array($item)) {
                    if (!isset($item['id'])) {
                        continue;
                    } elseif (!isset($item['default'])) {
                        $item['default'] = $default;
                    }
                } else {
                    $item = ['id' => $item, 'default' => $default];
                }
                list('id' => $itemId, 'default' => $itemDefault) = $item;
                if ($itemId != '') {
                    $value = $this->get($itemId, $itemDefault);
                    if (Identifier::isPath($itemId)) {
                        Recursive::set(Identifier::getKeys($itemId), $value, $data);
                    } else {
                        $data[$itemId] = $value;
                    }
                }
            }
            return $data;
        } elseif ($id != '') {
            if (Identifier::isPath($id)) {
                return Recursive::get(Identifier::getKeys($id), $this->data, $default);
            } elseif (isset($this->data[$id])) {
                return $this->data[$id];
            }
        }
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function has(array|int|string|null $id = null): bool
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
            if (Identifier::isPath($id)) {
                return Recursive::has(Identifier::getKeys($id), $this->data);
            } else {
                return isset($this->data[$id]);
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function del(array|int|string|null $id = null): static
    {
        if ($id === null) {
            $this->data = [];
        } elseif (is_array($id)) {
            foreach ($id as $itemId) {
                $this->del($itemId);
            }
        } elseif ($id != '') {
            if (Identifier::isPath($id)) {
                Recursive::del(Identifier::getKeys($id), $this->data);
            } else {
                unset($this->data[$id]);
            }
        }
        return $this;
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
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
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
    public function getIterator(array|int|string|null $id = null): Traversable
    {
        return new ArrayIterator((array)$this->get($id));
    }
}
