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
     * @param DataManagerInterface|array $config
     * @dataProvider providerConfigure
     */
    public function testConfigure(mixed $expected, DataManagerInterface | array $config)
    {
        $data = [];
        Recursive::configure(function (array $items) use (&$data): void {
            $data = $items;
        }, $config);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param mixed ...$items
     * @dataProvider providerJoin
     */
    public function testJoin(mixed $expected, mixed ...$items)
    {
        $this->assertEquals($expected, Recursive::join(...$items));
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param mixed $value
     * @param DataManagerInterface|array $data
     * @dataProvider providerSet
     */
    public function testSet(mixed $expected, array $keys, mixed $value, DataManagerInterface | array $data)
    {
        Recursive::set($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param mixed $value
     * @param DataManagerInterface|array $data
     * @dataProvider providerAdd
     */
    public function testAdd(mixed $expected, array $keys, mixed $value, DataManagerInterface | array $data)
    {
        Recursive::add($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param DataManagerInterface|array $data
     * @param mixed $default
     * @dataProvider providerGet
     */
    public function testGet(mixed $expected, array $keys, DataManagerInterface | array $data, mixed $default)
    {
        $this->assertEquals($expected, Recursive::get($keys, $data, $default));
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param DataManagerInterface|array $data
     * @dataProvider providerHas
     */
    public function testHas(mixed $expected, array $keys, DataManagerInterface | array $data)
    {
        $this->assertEquals($expected, Recursive::has($keys, $data));
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param DataManagerInterface|array $data
     * @dataProvider providerDel
     */
    public function testDel(mixed $expected, array $keys, DataManagerInterface | array $data)
    {
        Recursive::del($keys, $data);
        $this->assertEquals($expected, $data);
    }
}
