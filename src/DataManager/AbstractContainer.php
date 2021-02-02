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

use ArrayIterator;
use Qunity\Component\DataManager\Helper\Converter;
use Qunity\Component\DataManager\Helper\Recursive;
use Traversable;

/**
 * Class AbstractContainer
 * @package Qunity\Component\DataManager
 */
abstract class AbstractContainer implements ContainerInterface
{
    /**
     * Container data
     * @var array
     */
    protected array $data = [];

    /**
     * AbstractContainer constructor
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setElements($data);
    }

    /**
     * @inheritDoc
     */
    public function setElements(array $data): ContainerInterface
    {
        $this->data = [];
        foreach ($data as $path => $value) {
            if ($path != '') {
                $this->setElement($path, $value);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setElement(string | int $path, mixed $value): ContainerInterface
    {
        if ($path != '') {
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
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->getElements());
    }

    /**
     * @inheritDoc
     */
    public function getElements(array $paths = null): array
    {
        if ($paths !== null) {
            $data = [];
            foreach ($paths as $value) {
                if (is_array($value)) {
                    list('path' => $path, 'default' => $default) = $value;
                } else {
                    list('path' => $path, 'default' => $default) = ['path' => $value, 'default' => null];
                }
                if ($path != '') {
                    $data[$path] = $this->getElement($path, $default);
                }
            }
            return $data;
        }
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getElement(string | int $path, mixed $default = null): mixed
    {
        if ($path != '') {
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
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setElement($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->getElement($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->hasElement($offset);
    }

    /**
     * @inheritDoc
     */
    public function hasElement(string | int $path): bool
    {
        if ($path != '') {
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
    public function offsetUnset(mixed $offset): void
    {
        $this->delElement($offset);
    }

    /**
     * @inheritDoc
     */
    public function delElement(string | int $path): ContainerInterface
    {
        if ($path != '') {
            if (Converter::isPath($path)) {
                Recursive::del(Converter::getKeysByPath($path), $this->data);
            } else {
                unset($this->data[$path]);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addElements(array $data): ContainerInterface
    {
        foreach ($data as $path => $value) {
            if ($path != '') {
                $this->addElement($path, $value);
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addElement(string | int $path, mixed $value): ContainerInterface
    {
        if ($path != '') {
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
    public function hasElements(array $paths = null): bool
    {
        if ($paths !== null) {
            foreach ($paths as $path) {
                if ($path == '' || !$this->hasElement($path)) {
                    return false;
                }
            }
            return (bool)$paths;
        }
        return (bool)$this->data;
    }

    /**
     * @inheritDoc
     */
    public function delElements(array $paths = null): ContainerInterface
    {
        if ($paths !== null) {
            foreach ($paths as $path) {
                if ($path != '') {
                    $this->delElement($path);
                }
            }
        } else {
            $this->data = [];
        }
        return $this;
    }
}
