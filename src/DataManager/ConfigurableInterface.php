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
     * Configure object
     *
     * @param array<int|string,mixed>|DataManagerInterface|null $config
     * @return $this
     *
     * @see Recursive::configure
     */
    public function configure(array|DataManagerInterface $config = null): static;
}
