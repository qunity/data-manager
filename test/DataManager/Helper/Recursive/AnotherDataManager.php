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
use Qunity\Component\DataManager\ConfigurableInterface;
use Qunity\Component\DataManager\Helper\Recursive;
use Qunity\Component\DataManagerInterface;

/**
 * Class AnotherDataManager
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
 */
class AnotherDataManager extends DataManager implements ConfigurableInterface
{
    /**
     * Internal objects
     * @var array<mixed,object>
     */
    protected array $objects = [];

    /**
     * @inheritDoc
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function configure(array | DataManagerInterface $config = null): static
    {
        if ($config !== null) {
            Recursive::configure([$this, 'setObjects'], $config);
        }
        return $this;
    }

    /**
     * Set internal objects
     *
     * @param array<mixed,object> $objects
     * @return $this
     */
    // TODO: uninstall "phpcs:ignore" after updating squizlabs/php_codesniffer to v.3.6
    // phpcs:ignore Squiz.WhiteSpace.ScopeKeywordSpacing.Incorrect
    public function setObjects(array $objects): static
    {
        $this->objects = $objects;
        return $this;
    }
}
