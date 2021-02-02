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
use Qunity\Component\DataManager\ContainerInterface;
use Qunity\Component\DataManager\Helper\Recursive;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
 */
class Test extends TestCase
{
    use Provider;

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
     * @param ContainerInterface|array $data
     * @dataProvider providerSet
     */
    public function testSet(mixed $expected, array $keys, mixed $value, ContainerInterface | array $data)
    {
        Recursive::set($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param mixed $value
     * @param ContainerInterface|array $data
     * @dataProvider providerAdd
     */
    public function testAdd(mixed $expected, array $keys, mixed $value, ContainerInterface | array $data)
    {
        Recursive::add($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param ContainerInterface|array $data
     * @param mixed $default
     * @dataProvider providerGet
     */
    public function testGet(mixed $expected, array $keys, ContainerInterface | array $data, mixed $default)
    {
        $this->assertEquals($expected, Recursive::get($keys, $data, $default));
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param ContainerInterface|array $data
     * @dataProvider providerHas
     */
    public function testHas(mixed $expected, array $keys, ContainerInterface | array $data)
    {
        $this->assertEquals($expected, Recursive::has($keys, $data));
    }

    /**
     * @param mixed $expected
     * @param array $keys
     * @param ContainerInterface|array $data
     * @dataProvider providerDel
     */
    public function testDel(mixed $expected, array $keys, ContainerInterface | array $data)
    {
        Recursive::del($keys, $data);
        $this->assertEquals($expected, $data);
    }
}
