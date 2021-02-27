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
     * @param string|int $path
     * @dataProvider providerIsPath
     */
    public function testIsPath(mixed $expected, string | int $path)
    {
        $this->assertEquals($expected, Converter::isPath($path));
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param string|int $path
     * @param bool $throw
     * @dataProvider providerIsPathThrow
     */
    public function testIsPathThrow(mixed $expectedException, mixed $expectedMessage, string | int $path, bool $throw)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        Converter::isPath($path, $throw);
    }

    /**
     * @param mixed $expected
     * @param string|int $path
     * @dataProvider providerGetKeysByPath
     */
    public function testGetKeysByPath(mixed $expected, string | int $path)
    {
        $this->assertEquals($expected, Converter::getKeysByPath($path));
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @dataProvider providerGetPathByKeys
     */
    public function testGetPathByKeys(mixed $expected, array $keys)
    {
        $this->assertEquals($expected, Converter::getPathByKeys($keys));
    }

    /**
     * @param mixed $expected
     * @param string|int $path
     * @param string $prefix
     * @dataProvider providerGetMethodByPath
     */
    public function testGetMethodByPath(mixed $expected, string | int $path, string $prefix)
    {
        $this->assertEquals($expected, Converter::getMethodByPath($path, $prefix));
    }

    /**
     * @param mixed $expected
     * @param string $method
     * @param int $offset
     * @dataProvider providerGetPathByMethod
     */
    public function testGetPathByMethod(mixed $expected, string $method, int $offset)
    {
        $this->assertEquals($expected, Converter::getPathByMethod($method, $offset));
    }
}
