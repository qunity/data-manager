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
use Qunity\Component\DataManager\Helper\Converter;
use Qunity\Component\DataManager\Helper\Recursive;
use Traversable;

/**
 * Class AbstractDataManager
 * @package Qunity\Component
 *
 * TODO: implement disabling recursion
 */
abstract class AbstractDataManager implements DataManagerInterface
{
    /**
     * Object data
     * @var array<mixed>
     */
    protected array $data = [];

    /**
     * AbstractDataManager constructor
     * @param array<mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    /**
     * Call not existing methods
     *
     * @param string $method
     * @param array<mixed> $args
     *
     * @return mixed
     */
    public function __call(string $method, array $args): mixed
    {
        $callback = [$this, substr($method, 0, 3)];
        if (is_callable($callback) && method_exists(...$callback)) {
            return call_user_func($callback, Converter::getPathByMethod($method, 3), ...$args);
        }
        $class = $this::class;
        throw new BadMethodCallException("Call to invalid method {$class}::{$method}");
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
    public function getIterator(): Traversable
    {
        // TODO: make two variations of the result (flat and nested)
        return new ArrayIterator($this->get());
    }

    /**
     * @inheritDoc
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function set(array | int | string $path, mixed $value = null): static
    {
        if (is_array($path)) {
            $this->data = [];
            foreach ($path as $itemPath => $itemValue) {
                $this->set($itemPath, $itemValue);
            }
        } elseif ($path != '') {
            if (Converter::isPath($path)) {
                Recursive::set(Converter::getKeysByPath($path), $value, $this->data);
            } else {
                $this->data[$path] = $value;
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function add(array | int | string $path, mixed $value = null): static
    {
        if (is_array($path)) {
            foreach ($path as $itemPath => $itemValue) {
                $this->add($itemPath, $itemValue);
            }
        } elseif ($path != '') {
            if (Converter::isPath($path)) {
                Recursive::add(Converter::getKeysByPath($path), $value, $this->data);
            } elseif (isset($this->data[$path])) {
                $this->data[$path] = Recursive::join($this->data[$path], $value);
            } else {
                $this->data[$path] = $value;
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(array | int | string $path = null, mixed $default = null): mixed
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
                // TODO: make two variations of the result (flat and nested)
                $data[$itemPath] = $this->get($itemPath, $itemDefault);
            }
            return $data;
        } elseif ($path != '') {
            if (Converter::isPath($path)) {
                return Recursive::get(Converter::getKeysByPath($path), $this->data, $default);
            } elseif (isset($this->data[$path])) {
                return $this->data[$path];
            }
        }
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function has(array | int | string $path = null): bool
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
            if (Converter::isPath($path)) {
                return Recursive::has(Converter::getKeysByPath($path), $this->data);
            } else {
                return isset($this->data[$path]);
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function del(array | int | string $path = null): static
    {
        if ($path === null) {
            $this->data = [];
        } elseif (is_array($path)) {
            foreach ($path as $itemPath) {
                $this->del($itemPath);
            }
        } elseif ($path != '') {
            if (Converter::isPath($path)) {
                Recursive::del(Converter::getKeysByPath($path), $this->data);
            } else {
                unset($this->data[$path]);
            }
        }
        return $this;
    }
}
