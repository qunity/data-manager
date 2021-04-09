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

namespace Qunity\UnitTest\Component\DataManagerFactory;

use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManagerFactory;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManagerFactory
 */
class Test extends TestCase
{
    use Provider;

    /**
     * @param mixed $expectedInstanceOf
     * @param mixed $expectedData
     * @param mixed $data
     * @param mixed $class
     * @return void
     * @dataProvider providerSuccessCreate
     */
    public function testSuccessCreate(mixed $expectedInstanceOf, mixed $expectedData, mixed $data, mixed $class)
    {
        if ($data === [] && $class === null) {
            $object = DataManagerFactory::create();
        } elseif ($class === null) {
            $object = DataManagerFactory::create($data);
        } else {
            $object = DataManagerFactory::create($data, $class);
        }
        $this->assertInstanceOf($expectedInstanceOf, $object);
        $this->assertEquals($expectedData, $object);
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param mixed $data
     * @param mixed $class
     * @return void
     * @dataProvider providerErrorCreate
     */
    public function testErrorCreate(mixed $expectedException, mixed $expectedMessage, mixed $data, mixed $class)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        DataManagerFactory::create($data, $class);
    }
}
