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

namespace Qunity\UnitTest\Component\DataManager\Helper\Data;

use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManager\Helper\Data;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManager\Helper\Data
 */
class Test extends TestCase
{
    use Provider;

    /**
     * @param mixed $expected
     * @param mixed ...$items
     * @return void
     * @dataProvider providerJoin
     */
    public function testJoin(mixed $expected, mixed ...$items)
    {
        $this->assertEquals($expected, Data::join(...$items));
    }
}
