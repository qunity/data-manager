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

use Qunity\Component\DataManager\ContainerInterface;

/**
 * Interface DataManagerInterface
 * @package Qunity\Component
 */
interface DataManagerInterface
{
    /**
     * Get exist / create new container
     *
     * @param string|int|null $name
     * @param ContainerInterface|array|null $container
     *
     * @return ContainerInterface
     */
    public function container(
        string|int $name = null,
        ContainerInterface|array $container = null
    ): ContainerInterface;

    /**
     * Set data into container
     *
     * @param array|string|int $path
     * @param mixed $value
     * @param string|int|null $container
     *
     * @return $this
     */
    public function set(
        array|string|int $path,
        mixed $value = null,
        string|int $container = null
    ): self;

    /**
     * Add data into container
     *
     * @param array|string|int $path
     * @param mixed $value
     * @param string|int|null $container
     *
     * @return $this
     */
    public function add(
        array|string|int $path,
        mixed $value = null,
        string|int $container = null
    ): self;

    /**
     * Get data from container
     *
     * @param array|string|int|null $path
     * @param mixed $default
     * @param string|int|null $container
     *
     * @return mixed
     */
    public function get(
        array|string|int $path = null,
        mixed $default = null,
        string|int $container = null
    ): mixed;

    /**
     * Check existence data in container
     *
     * @param array|string|int|null $path
     * @param string|int|null $container
     *
     * @return bool
     */
    public function has(
        array|string|int $path = null,
        string|int $container = null
    ): bool;

    /**
     * Remove data from container
     *
     * @param array|string|int|null $path
     * @param string|int|null $container
     *
     * @return $this
     */
    public function del(
        array|string|int $path = null,
        string|int $container = null
    ): self;
}
