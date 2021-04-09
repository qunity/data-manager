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

use ArrayAccess;
use IteratorAggregate;

/**
 * Interface DataManagerInterface
 * @package Qunity\Component
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
     * Set data into object
     *
     * @param array<int|string,mixed>|int|string $path
     * @param mixed|null $value
     *
     * @return $this
     */
    public function set(array|int|string $path, mixed $value = null): static;

    /**
     * Add data into object
     *
     * @param array<int|string,mixed>|int|string $path
     * @param mixed|null $value
     *
     * @return $this
     */
    public function add(array|int|string $path, mixed $value = null): static;

    /**
     * Get data from object
     *
     * @param array<array|int|string>|int|string|null $path
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(array|int|string $path = null, mixed $default = null): mixed;

    /**
     * Check existence data in object
     *
     * @param array<array|int|string>|int|string|null $path
     * @return bool
     */
    public function has(array|int|string $path = null): bool;

    /**
     * Remove data from object
     *
     * @param array<array|int|string>|int|string|null $path
     * @return $this
     */
    public function del(array|int|string $path = null): static;
}
