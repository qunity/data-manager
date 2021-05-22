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

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManager\Helper\Recursive
 */
class Test extends TestCase
{
    use Provider;

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @param mixed $value
     * @param mixed $data
     * @return void
     * @dataProvider providerSet
     */
    public function testSet(mixed $expected, mixed $keys, mixed $value, mixed $data)
    {
        Recursive::set($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @param mixed $value
     * @param mixed $data
     * @return void
     * @dataProvider providerAdd
     */
    public function testAdd(mixed $expected, mixed $keys, mixed $value, mixed $data)
    {
        Recursive::add($keys, $value, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @param mixed $data
     * @param mixed $default
     * @return void
     * @dataProvider providerGet
     */
    public function testGet(mixed $expected, mixed $keys, mixed $data, mixed $default)
    {
        if ($default === null) {
            $this->assertEquals($expected, Recursive::get($keys, $data));
        } else {
            $this->assertEquals($expected, Recursive::get($keys, $data, $default));
        }
    }

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @param mixed $data
     * @return void
     * @dataProvider providerHas
     */
    public function testHas(mixed $expected, mixed $keys, mixed $data)
    {
        $this->assertEquals($expected, Recursive::has($keys, $data));
    }

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @param mixed $data
     * @return void
     * @dataProvider providerDel
     */
    public function testDel(mixed $expected, mixed $keys, mixed $data)
    {
        Recursive::del($keys, $data);
        $this->assertEquals($expected, $data);
    }

    /**
     * @param mixed $expected
     * @param mixed $keys
     * @param mixed $data
     * @param mixed $check
     * @return void
     * @dataProvider providerTry
     */
    public function testTry(mixed $expected, mixed $keys, mixed $data, mixed $check)
    {
        if ($check === null) {
            $this->assertEquals($expected, Recursive::try($keys, $data));
        } else {
            $this->assertEquals($expected, Recursive::try($keys, $data, $check));
        }
    }
}
