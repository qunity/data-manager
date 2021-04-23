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

namespace Qunity\UnitTest\Component\DataManager\Helper\Identifier;

use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManager\Helper\Identifier;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManager\Helper\Identifier
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
     * @dataProvider providerClearId
     */
    public function testClearId(mixed $expected, mixed $id)
    {
        $this->assertEquals($expected, Identifier::clearId($id));
    }

    /**
     * @param mixed $expected
     * @param mixed $ids
     * @return void
     * @dataProvider providerClearIds
     */
    public function testClearIds(mixed $expected, mixed $ids)
    {
        $this->assertEquals($expected, Identifier::clearIds($ids));
    }

    /**
     * @param mixed $expected
     * @param mixed $id
     * @return void
     * @dataProvider providerGetKeysById
     */
    public function testGetKeysById(mixed $expected, mixed $id)
    {
        $this->assertEquals($expected, Identifier::getKeysById($id));
    }

    /**
     * @param mixed $expected
     * @param mixed $ids
     * @return void
     * @dataProvider providerGetPathByIds
     */
    public function testGetPathByIds(mixed $expected, mixed $ids)
    {
        $this->assertEquals($expected, Identifier::getPathByIds($ids));
    }

    /**
     * @param mixed $expected
     * @param mixed $id
     * @param mixed $prefix
     * @return void
     * @dataProvider providerGetMethodById
     */
    public function testGetMethodById(mixed $expected, mixed $id, mixed $prefix)
    {
        if ($prefix === null) {
            $this->assertEquals($expected, Identifier::getMethodById($id));
        } else {
            $this->assertEquals($expected, Identifier::getMethodById($id, $prefix));
        }
    }

    /**
     * @param mixed $expected
     * @param mixed $method
     * @param mixed $offset
     * @return void
     * @dataProvider providerGetIdByMethod
     */
    public function testGetIdByMethod(mixed $expected, mixed $method, mixed $offset)
    {
        if ($offset === null) {
            $this->assertEquals($expected, Identifier::getIdByMethod($method));
        } else {
            $this->assertEquals($expected, Identifier::getIdByMethod($method, $offset));
        }
    }
}
