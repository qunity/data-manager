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

use ArrayAccess;
use IteratorAggregate;

/**
 * Interface ContainerInterface
 * @package Qunity\Component\DataManager
 */
interface ContainerInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * Path delimiter for container item
     */
    public const DELIMITER_PATH = '/';

    /**
     * Key delimiter for container item
     */
    public const DELIMITER_KEY = '_';

    /**
     * Set elements into container data
     *
     * @param array $data
     * @return $this
     */
    public function setElements(array $data): self;

    /**
     * Set element into container data
     *
     * @param string|int $path
     * @param mixed $value
     *
     * @return $this
     */
    public function setElement(string | int $path, mixed $value): self;

    /**
     * Add elements into container data
     *
     * @param array $data
     * @return $this
     */
    public function addElements(array $data): self;

    /**
     * Add element into container data
     *
     * @param string|int $path
     * @param mixed $value
     *
     * @return $this
     */
    public function addElement(string | int $path, mixed $value): self;

    /**
     * Get elements from container data
     *
     * @param array|null $paths
     * @return mixed[]
     */
    public function getElements(array $paths = null): array;

    /**
     * Get element from container data
     *
     * @param string|int $path
     * @param mixed $default
     *
     * @return mixed
     */
    public function getElement(string | int $path, mixed $default = null): mixed;

    /**
     * Check existence elements in container data
     *
     * @param array|null $paths
     * @return bool
     */
    public function hasElements(array $paths = null): bool;

    /**
     * Check existence element in container data
     *
     * @param string|int $path
     * @return bool
     */
    public function hasElement(string | int $path): bool;

    /**
     * Remove elements from container data
     *
     * @param array|null $paths
     * @return $this
     */
    public function delElements(array $paths = null): self;

    /**
     * Remove element from container data
     *
     * @param string|int $path
     * @return $this
     */
    public function delElement(string | int $path): self;
}
