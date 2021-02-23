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

namespace Qunity\UnitTest\Component\DataManager\Helper\Recursive;

use Qunity\Component\DataManager;

/**
 * Class AnotherDataManager
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
 */
class AnotherDataManager extends DataManager
{
    /**
     * Internal instances
     * @var array
     */
    protected array $instances = [];

    /**
     * Configure manager
     * @param array $instances
     */
    public function configure(array $instances): void
    {
        $this->setInstances($instances);
    }

    /**
     * Set internal instances
     *
     * @param array $instances
     * @return $this
     */
    public function setInstances(array $instances): self
    {
        $this->instances = $instances;
        return $this;
    }
}
