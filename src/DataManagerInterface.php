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

use ArrayAccess;
use IteratorAggregate;
use Traversable;

/**
 * Interface DataManagerInterface
 * @package Qunity
 *
 * @extends ArrayAccess<mixed,mixed>
 * @extends IteratorAggregate<mixed>
 */
interface DataManagerInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * Path delimiter for item
     */
    public const DELIMITER_PATH = '/';

    /**
     * Key delimiter for item
     */
    public const DELIMITER_KEY = '_';

    /**
     * Get external iterator
     *
     * @param array<array|int|string>|int|string|null $id
     * @return Traversable<mixed>
     */
    public function getIterator(array|int|string|null $id = null): Traversable;

    /**
     * Set data into object
     *
     * @param array<int|string,mixed>|int|string $id
     * @param mixed|null $value
     *
     * @return $this
     */
    public function set(array|int|string $id, mixed $value = null): static;

    /**
     * Add data into object
     *
     * @param array<int|string,mixed>|int|string $id
     * @param mixed|null $value
     *
     * @return $this
     */
    public function add(array|int|string $id, mixed $value = null): static;

    /**
     * Get data from object
     *
     * @param array<array|int|string>|int|string|null $id
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(array|int|string|null $id = null, mixed $default = null): mixed;

    /**
     * Check existence data in object
     *
     * @param array<array|int|string>|int|string|null $id
     * @return bool
     */
    public function has(array|int|string|null $id = null): bool;

    /**
     * Remove data from object
     *
     * @param array<array|int|string>|int|string|null $id
     * @return $this
     */
    public function del(array|int|string|null $id = null): static;

    /**
     * Try check data in object
     *
     * @param array<array|int|string>|int|string|null $id
     * @param callable|null $check
     *
     * @return bool
     */
    public function try(array|int|string|null $id = null, callable|null $check = null): bool;
}
