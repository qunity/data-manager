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

/**
 * Trait AbstractProvider
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
trait AbstractProvider
{
    /**
     * @return array[]
     */
    abstract public function providerGetIterator(): array;

    /**
     * @return array[]
     */
    abstract public function providerArrayAccess(): array;

    /**
     * @return array[]
     */
    abstract public function providerSuccessMagicMethods(): array;

    /**
     * @return array[]
     */
    abstract public function providerErrorMagicMethods(): array;

    /**
     * @return array[]
     */
    abstract public function providerSingleMethods(): array;

    /**
     * @return array[]
     */
    abstract public function providerMassMethods(): array;
}
