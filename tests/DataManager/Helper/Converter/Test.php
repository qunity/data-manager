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

namespace Qunity\UnitTest\Component\DataManager\Helper\Converter;

use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManager\Helper\Converter;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManager\Helper\Converter
 */
class Test extends TestCase
{
    use Provider;

    /**
     * @param mixed $expected
     * @param mixed $path
     * @param mixed $throw
     * @return void
     * @dataProvider providerIsPath
     */
    public function testIsPath(
        mixed $expected,
        mixed $path,
        mixed $throw
    ) {
        if ($throw === null) {
            $this->assertEquals($expected, Converter::isPath($path));
        } else {
            $this->assertEquals($expected, Converter::isPath($path, $throw));
        }
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param mixed $path
     * @param mixed $throw
     * @return void
     * @dataProvider providerIsPathThrow
     */
    public function testIsPathThrow(
        mixed $expectedException,
        mixed $expectedMessage,
        mixed $path,
        mixed $throw
    ) {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        Converter::isPath($path, $throw);
    }

    /**
     * @param mixed $expected
     * @param mixed $path
     * @return void
     * @dataProvider providerClearPath
     */
    public function testClearPath(
        mixed $expected,
        mixed $path
    ) {
        $this->assertEquals($expected, Converter::clearPath($path));
    }

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @return void
     * @dataProvider providerClearKeys
     */
    public function testClearKeys(
        mixed $expected,
        mixed $keys
    ) {
        $this->assertEquals($expected, Converter::clearKeys($keys));
    }

    /**
     * @param mixed $expected
     * @param mixed $path
     * @return void
     * @dataProvider providerGetKeysByPath
     */
    public function testGetKeysByPath(
        mixed $expected,
        mixed $path
    ) {
        $this->assertEquals($expected, Converter::getKeysByPath($path));
    }

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @return void
     * @dataProvider providerGetPathByKeys
     */
    public function testGetPathByKeys(
        mixed $expected,
        mixed $keys
    ) {
        $this->assertEquals($expected, Converter::getPathByKeys($keys));
    }

    /**
     * @param mixed $expected
     * @param mixed $path
     * @param mixed $prefix
     * @return void
     * @dataProvider providerGetMethodByPath
     */
    public function testGetMethodByPath(
        mixed $expected,
        mixed $path,
        mixed $prefix
    ) {
        if ($prefix === null) {
            $this->assertEquals($expected, Converter::getMethodByPath($path));
        } else {
            $this->assertEquals($expected, Converter::getMethodByPath($path, $prefix));
        }
    }

    /**
     * @param mixed $expected
     * @param mixed $method
     * @param mixed $offset
     * @return void
     * @dataProvider providerGetPathByMethod
     */
    public function testGetPathByMethod(
        mixed $expected,
        mixed $method,
        mixed $offset
    ) {
        if ($offset === null) {
            $this->assertEquals($expected, Converter::getPathByMethod($method));
        } else {
            $this->assertEquals($expected, Converter::getPathByMethod($method, $offset));
        }
    }
}
