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

namespace Qunity\UnitTest\Component\AbstractDataManager;

use Qunity\Component\DataManager;

/**
 * Class AnotherDataManager
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
class AnotherDataManager extends DataManager
{
    /**#@+
     * @inheritDoc
     */
    protected const DEFAULT_CONTAINER_NAME = 'another';
    protected const DEFAULT_CONTAINER_DATA = ['test' => 'value'];
    protected const DEFAULT_CONTAINER_CLASS = AnotherContainer::class;
    /**#@-*/
}
