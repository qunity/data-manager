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

use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManager\Helper\Recursive;
use Qunity\Component\DataManagerInterface;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
 */
class Test extends TestCase
{
    use Provider;

    /**
     * @param mixed $expected
     * @param array<mixed,mixed>|DataManagerInterface $config
     * @return void
     * @dataProvider providerSuccessConfigure
     */
    public function testSuccessConfigure(mixed $expected, array | DataManagerInterface $config)
    {
        $actual = [];
        Recursive::configure(function (array $objects) use (&$actual): void {
            $actual = $objects;
        }, $config);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param array<mixed,mixed>|DataManagerInterface $config
     * @return void
     * @dataProvider providerErrorConfigure
     */
    public function testErrorConfigure(
        mixed $expectedException,
        mixed $expectedMessage,
        array | DataManagerInterface $config
    ) {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        Recursive::configure(function (array $objects): void {
            unset($objects);
        }, $config);
    }

    /**
     * @param mixed $expected
     * @param mixed ...$items
     * @return void
     * @dataProvider providerJoin
     */
    public function testJoin(mixed $expected, mixed ...$items)
    {
        $this->assertEquals($expected, Recursive::join(...$items));
    }

    /**
     * @param mixed $expected
     * @param array<int|string,int|string> $keys
     * @param mixed $value
     * @param array<mixed,mixed>|DataManagerInterface $data
     * @return void
     * @dataProvider providerSet
     */
    public function testSet(mixed $expected, array $keys, mixed $value, array | DataManagerInterface $data)
    {
        Recursive::set($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param array<int|string,int|string> $keys
     * @param mixed $value
     * @param array<mixed,mixed>|DataManagerInterface $data
     * @return void
     * @dataProvider providerAdd
     */
    public function testAdd(mixed $expected, array $keys, mixed $value, array | DataManagerInterface $data)
    {
        Recursive::add($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param array<int|string,int|string> $keys
     * @param array<mixed,mixed>|DataManagerInterface $data
     * @param mixed $default
     * @return void
     * @dataProvider providerGet
     */
    public function testGet(mixed $expected, array $keys, array | DataManagerInterface $data, mixed $default)
    {
        if ($default === null) {
            $this->assertEquals($expected, Recursive::get($keys, $data));
        } else {
            $this->assertEquals($expected, Recursive::get($keys, $data, $default));
        }
    }

    /**
     * @param mixed $expected
     * @param array<int|string,int|string> $keys
     * @param array<mixed,mixed>|DataManagerInterface $data
     * @return void
     * @dataProvider providerHas
     */
    public function testHas(mixed $expected, array $keys, array | DataManagerInterface $data)
    {
        $this->assertEquals($expected, Recursive::has($keys, $data));
    }

    /**
     * @param mixed $expected
     * @param array<int|string,int|string> $keys
     * @param array<mixed,mixed>|DataManagerInterface $data
     * @return void
     * @dataProvider providerDel
     */
    public function testDel(mixed $expected, array $keys, array | DataManagerInterface $data)
    {
        Recursive::del($keys, $data);
        $this->assertEquals($expected, $data);
    }
}
