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
     * @param array|string|int $path
     * @param mixed $value
     *
     * @return $this
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function set(array | string | int $path, mixed $value = null): static;

    /**
     * Add data into object
     *
     * @param array|string|int $path
     * @param mixed $value
     *
     * @return $this
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function add(array | string | int $path, mixed $value = null): static;

    /**
     * Get data from object
     *
     * @param array|string|int|null $path
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(array | string | int $path = null, mixed $default = null): mixed;

    /**
     * Check existence data in object
     *
     * @param array|string|int|null $path
     * @return bool
     */
    public function has(array | string | int $path = null): bool;

    /**
     * Remove data from object
     *
     * @param array|string|int|null $path
     * @return $this
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function del(array | string | int $path = null): static;
}
