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
     * @param array $data
     * @param string $class
     * @dataProvider providerSuccessCreate
     */
    public function testSuccessCreate(mixed $expectedInstanceOf, mixed $expectedData, array $data, string $class)
    {
        $dataManager = DataManagerFactory::create($data, $class);
        $this->assertInstanceOf($expectedInstanceOf, $dataManager);
        $this->assertEquals($expectedData, $dataManager);
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param array $data
     * @param string $class
     * @dataProvider providerErrorCreate
     */
    public function testErrorCreate(mixed $expectedException, mixed $expectedMessage, array $data, string $class)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        DataManagerFactory::create($data, $class);
    }
}
