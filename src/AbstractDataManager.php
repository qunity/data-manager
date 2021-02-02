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

use Qunity\Component\DataManager\Container;
use Qunity\Component\DataManager\ContainerFactory;
use Qunity\Component\DataManager\ContainerInterface;
use Qunity\Component\DataManager\Helper\Converter;

/**
 * Class AbstractDataManager
 * @package Qunity\Component
 */
abstract class AbstractDataManager implements DataManagerInterface
{
    /**#@+
     * Default container information
     */
    protected const DEFAULT_CONTAINER_NAME = 'main';
    protected const DEFAULT_CONTAINER_DATA = [];
    protected const DEFAULT_CONTAINER_CLASS = Container::class;
    /**#@-*/

    /**
     * Containers list
     * @var ContainerInterface[]
     */
    protected array $containers = [];

    /**
     * AbstractDataManager constructor
     * @param ContainerInterface[]|array $containers
     */
    public function __construct(array $containers = [])
    {
        foreach ($containers as $name => $container) {
            $this->container($name, $container);
        }
    }

    /**
     * @inheritDoc
     */
    public function container(
        string|int $name = null,
        ContainerInterface|array $container = null
    ): ContainerInterface
    {
        if ($name === null || $name == '') {
            $name = static::DEFAULT_CONTAINER_NAME;
        }
        if (!isset($this->containers[$name])) {
            if ($container instanceof ContainerInterface) {
                $this->containers[$name] = $container;
            } else {
                $this->containers[$name] = ContainerFactory::create(
                    ($container['data'] ?? static::DEFAULT_CONTAINER_DATA),
                    ($container['class'] ?? static::DEFAULT_CONTAINER_CLASS)
                );
            }
        }
        return $this->containers[$name];
    }

    /**
     * Call not existing methods
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call(string $method, array $args): mixed
    {
        $keys = Converter::getKeysByPath(Converter::getPathByMethod($method, 3));
        $param[] = Converter::getPathByKeys(array_slice($keys, 0, -1));
        if ($args != []) {
            $param[] = reset($args);
        }
        $param[] = end($keys);
        return call_user_func_array([$this, substr($method, 0, 3)], $param);
    }

    /**
     * @inheritDoc
     */
    public function set(
        array|string|int $path,
        mixed $value = null,
        string|int $container = null
    ): DataManagerInterface
    {
        if (is_array($path)) {
            $this->container($container)->setElements($path);
        } else {
            $this->container($container)->setElement($path, $value);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function add(
        array|string|int $path,
        mixed $value = null,
        string|int $container = null
    ): DataManagerInterface
    {
        if (is_array($path)) {
            $this->container($container)->addElements($path);
        } else {
            $this->container($container)->addElement($path, $value);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(
        array|string|int $path = null,
        mixed $default = null,
        string|int $container = null
    ): mixed
    {
        if ($path === null || is_array($path)) {
            return $this->container($container)->getElements($path);
        } else {
            return $this->container($container)->getElement($path, $default);
        }
    }

    /**
     * @inheritDoc
     */
    public function has(
        array|string|int $path = null,
        string|int $container = null
    ): bool
    {
        if ($path === null || is_array($path)) {
            return $this->container($container)->hasElements($path);
        } else {
            return $this->container($container)->hasElement($path);
        }
    }

    /**
     * @inheritDoc
     */
    public function del(
        array|string|int $path = null,
        string|int $container = null
    ): DataManagerInterface
    {
        if ($path === null || is_array($path)) {
            $this->container($container)->delElements($path);
        } else {
            $this->container($container)->delElement($path);
        }
        return $this;
    }
}
