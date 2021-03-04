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
     * @param int|string $value
     * @param bool|null $throw
     * @dataProvider providerIsPath
     */
    public function testIsPath(mixed $expected, int | string $value, ?bool $throw)
    {
        if ($throw === null) {
            $this->assertEquals($expected, Converter::isPath($value));
        } else {
            $this->assertEquals($expected, Converter::isPath($value, $throw));
        }
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param int|string $value
     * @param bool $throw
     * @dataProvider providerIsPathThrow
     */
    public function testIsPathThrow(mixed $expectedException, mixed $expectedMessage, int | string $value, bool $throw)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        Converter::isPath($value, $throw);
    }

    /**
     * @param mixed $expected
     * @param int|string $path
     * @dataProvider providerClearPath
     */
    public function testClearPath(mixed $expected, int | string $path)
    {
        $this->assertEquals($expected, Converter::clearPath($path));
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @dataProvider providerClearKeys
     */
    public function testClearKeys(mixed $expected, array $keys)
    {
        $this->assertEquals($expected, Converter::clearKeys($keys));
    }

    /**
     * @param mixed $expected
     * @param int|string $path
     * @dataProvider providerGetKeysByPath
     */
    public function testGetKeysByPath(mixed $expected, int | string $path)
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
     * @param int|string $path
     * @param string|null $prefix
     * @dataProvider providerGetMethodByPath
     */
    public function testGetMethodByPath(mixed $expected, int | string $path, ?string $prefix)
    {
        if ($prefix === null) {
            $this->assertEquals($expected, Converter::getMethodByPath($path));
        } else {
            $this->assertEquals($expected, Converter::getMethodByPath($path, $prefix));
        }
    }

    /**
     * @param mixed $expected
     * @param string $method
     * @param int|null $offset
     * @dataProvider providerGetPathByMethod
     */
    public function testGetPathByMethod(mixed $expected, string $method, ?int $offset)
    {
        if ($offset === null) {
            $this->assertEquals($expected, Converter::getPathByMethod($method));
        } else {
            $this->assertEquals($expected, Converter::getPathByMethod($method, $offset));
        }
    }
}
