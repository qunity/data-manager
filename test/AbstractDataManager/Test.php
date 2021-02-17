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

namespace Qunity\UnitTest\Component\AbstractDataManager;

use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManager\ContainerInterface;
use Qunity\Component\DataManagerFactory;
use Qunity\Component\DataManagerInterface;
use Qunity\UnitTest\Component\DataManager\AbstractContainer\Helper as AbstractContainerHelper;
use Qunity\UnitTest\Component\DataManager\AbstractContainer\Provider as AbstractContainerProvider;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
class Test extends TestCase
{
    use Provider;
    use AbstractContainerHelper;
    use AbstractContainerProvider;

    /**
     * @param mixed $expectedInstanceOf
     * @param mixed $expectedData
     * @param string|int $name
     * @param ContainerInterface|array $container
     * @dataProvider providerContainer
     */
    public function testContainer(
        mixed $expectedInstanceOf,
        mixed $expectedData,
        string | int $name,
        ContainerInterface | array $container
    ) {
        $dataManager = DataManagerFactory::create();
        $this->assertInstanceOf($expectedInstanceOf, $dataManager->container($name, $container));
        $this->assertEquals($expectedData, $dataManager->container($name)->getElements());
    }

    /**
     * @param mixed $expected
     * @param DataManagerInterface $dataManager
     * @param string $method
     * @param mixed ...$args
     * @dataProvider providerMagicMethods
     */
    public function testMagicMethods(
        mixed $expected,
        DataManagerInterface $dataManager,
        string $method,
        mixed ...$args
    ) {
        $this->assertEquals($expected, call_user_func_array([$dataManager, $method], $args));
    }

    /**
     * @param array $step1
     * @param array $step2
     * @param array $step3
     * @dataProvider providerSingleMethods
     */
    public function testSingleMethods(array $step1, array $step2, array $step3)
    {
        $object = DataManagerFactory::create();

        list($path, $value) = $step1;

        $this->assertFalse($object->has($path));
        $this->assertNull($object->get($path));
        $this->assertEquals('default', $object->get($path, 'default'));

        $this->assertInstanceOf(DataManagerInterface::class, $object->set($path, $value));

        $this->assertTrue($object->has($path));
        $this->assertEquals($value, $object->get($path));
        $this->assertEquals($value, $object->get($path, 'default'));

        list($path, $value) = $step2;

        $this->assertInstanceOf(DataManagerInterface::class, $object->add($path, $value));

        list($path, $value) = $step3;

        $this->assertTrue($object->has($path));
        $this->assertEquals($value, $object->get($path));
        $this->assertEquals($value, $object->get($path, 'default'));

        $this->assertInstanceOf(DataManagerInterface::class, $object->del($path));

        $this->assertFalse($object->has($path));
        $this->assertNull($object->get($path));
        $this->assertEquals('default', $object->get($path, 'default'));
    }

    /**
     * @param array $step1
     * @param array $step2
     * @param array $step3
     * @dataProvider providerMassMethods
     */
    public function testMassMethods(array $step1, array $step2, array $step3)
    {
        $object = DataManagerFactory::create();

        list(
            'data' => $data,
            'flat' => $flat,
            'real' => $real,
            'paths' => $paths,
            'dataNull' => $dataNull,
            'dataDefault' => $dataDefault,
            'pathsDefault' => $pathsDefault
            ) = $this->stepMassMethods($step1);

        $this->assertFalse($object->has());
        $this->assertFalse($object->has($paths));
        $this->assertEquals([], $object->get());
        $this->assertEquals($dataNull, $object->get($paths));
        $this->assertEquals($dataDefault, $object->get($pathsDefault));

        $this->assertInstanceOf(DataManagerInterface::class, $object->set($data));

        $this->assertTrue($object->has());
        $this->assertTrue($object->has($paths));
        $this->assertEquals($real, $object->get());
        $this->assertEquals($flat, $object->get($paths));
        $this->assertEquals($flat, $object->get($pathsDefault));

        list(
            'data' => $data,
            'flat' => $flat,
            'real' => $real,
            'paths' => $paths,
            'pathsDefault' => $pathsDefault
            ) = $this->stepMassMethods($step2);

        $this->assertInstanceOf(DataManagerInterface::class, $object->add($data));

        $this->assertTrue($object->has());
        $this->assertTrue($object->has($paths));
        $this->assertEquals($real, $object->get());
        $this->assertEquals($flat, $object->get($paths));
        $this->assertEquals($flat, $object->get($pathsDefault));

        list(
            'real' => $real,
            'paths' => $paths,
            'dataNull' => $dataNull,
            'dataDefault' => $dataDefault,
            'pathsDefault' => $pathsDefault
            ) = $this->stepMassMethods($step3);

        $this->assertInstanceOf(DataManagerInterface::class, $object->del($paths));

        $this->assertTrue($object->has());
        $this->assertFalse($object->has($paths));
        $this->assertEquals($real, $object->get());
        $this->assertEquals($dataNull, $object->get($paths));
        $this->assertEquals($dataDefault, $object->get($pathsDefault));

        $this->assertInstanceOf(DataManagerInterface::class, $object->del());

        $this->assertFalse($object->has());
        $this->assertFalse($object->has($paths));
        $this->assertEquals([], $object->get());
        $this->assertEquals($dataNull, $object->get($paths));
        $this->assertEquals($dataDefault, $object->get($pathsDefault));
    }
}