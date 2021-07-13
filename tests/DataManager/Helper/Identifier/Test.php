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

namespace Qunity\UnitTest\DataManager\Helper\Identifier;

use PHPUnit\Framework\TestCase;
use Qunity\DataManager\Helper\Identifier;

/**
 * Class Test
 * @package Qunity\UnitTest\DataManager\Helper\Identifier
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Test extends TestCase
{
    use Provider;

    /**
     * @param mixed $expected
     * @param mixed $id
     * @param mixed $throw
     * @return void
     * @dataProvider providerIsPath
     */
    public function testIsPath(mixed $expected, mixed $id, mixed $throw)
    {
        if ($throw === null) {
            $this->assertEquals($expected, Identifier::isPath($id));
        } else {
            $this->assertEquals($expected, Identifier::isPath($id, $throw));
        }
    }

    /**
     * @param mixed $eException
     * @param mixed $eMessage
     * @param mixed $id
     * @param mixed $throw
     * @return void
     * @dataProvider providerIsPathThrow
     */
    public function testIsPathThrow(mixed $eException, mixed $eMessage, mixed $id, mixed $throw)
    {
        $this->expectException($eException);
        $this->expectExceptionMessage($eMessage);
        Identifier::isPath($id, $throw);
    }

    /**
     * @param mixed $expected
     * @param mixed $id
     * @return void
     * @dataProvider providerGetKeys
     */
    public function testGetKeys(mixed $expected, mixed $id)
    {
        $this->assertEquals($expected, Identifier::getKeys($id));
    }

    /**
     * @param mixed $expected
     * @param mixed $method
     * @param mixed $offset
     * @return void
     * @dataProvider providerGetUnderscore
     */
    public function testGetUnderscore(mixed $expected, mixed $method, mixed $offset)
    {
        if ($offset === null) {
            $this->assertEquals($expected, Identifier::getUnderscore($method));
        } else {
            $this->assertEquals($expected, Identifier::getUnderscore($method, $offset));
        }
    }
}
