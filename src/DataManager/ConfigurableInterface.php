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

use Qunity\Component\DataManager\Helper\Recursive;
use Qunity\Component\DataManagerInterface;

/**
 * Interface ConfigurableInterface
 * @package Qunity\Component\DataManager
 */
interface ConfigurableInterface
{
    /**
     * Configure manager
     *
     * @param DataManagerInterface|array $config
     * @return $this
     *
     * @see Recursive::configure
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function configure(DataManagerInterface | array $config): static;
}
