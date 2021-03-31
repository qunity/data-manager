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

use Exception;
use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManagerFactory;
use Qunity\Component\DataManagerInterface;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
class Test extends TestCase
{
    use Provider;
    use Helper;

    /**
     * @param mixed $expected
     * @param DataManagerInterface $dataManager
     * @return void
     * @throws Exception
     * @dataProvider providerGetIterator
     */
    public function testGetIterator(mixed $expected, DataManagerInterface $dataManager)
    {
        $this->assertEquals($expected, $dataManager->getIterator());
    }

    /**
     * @param int|string $path
     * @param mixed $value
     * @return void
     * @dataProvider providerArrayAccess
     */
    public function testArrayAccess(int | string $path, mixed $value)
    {
        $object = DataManagerFactory::create();

        $this->assertFalse(isset($object[$path]));
        $this->assertNull($object[$path]);
        $object[$path] = $value;

        $this->assertTrue(isset($object[$path]));
        $this->assertEquals($value, $object[$path]);

        unset($object[$path]);
        $this->assertFalse(isset($object[$path]));
        $this->assertNull($object[$path]);
    }

    /**
     * @param mixed $expected
     * @param DataManagerInterface $dataManager
     * @param string $method
     * @param mixed ...$args
     * @return void
     * @dataProvider providerSuccessMagicMethods
     */
    public function testSuccessMagicMethods(
        mixed $expected,
        DataManagerInterface $dataManager,
        string $method,
        mixed ...$args
    ) {
        // @phpstan-ignore-next-line
        $this->assertEquals($expected, call_user_func_array([$dataManager, $method], $args));
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param DataManagerInterface $dataManager
     * @param string $method
     * @return void
     * @dataProvider providerErrorMagicMethods
     */
    public function testErrorMagicMethods(
        mixed $expectedException,
        mixed $expectedMessage,
        DataManagerInterface $dataManager,
        string $method
    ) {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        call_user_func([$dataManager, $method]); // @phpstan-ignore-line
    }

    /**
     * @param array<mixed> $step1
     * @param array<mixed> $step2
     * @param array<mixed> $step3
     * @return void
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
     * @param array<mixed> $step1
     * @param array<mixed> $step2
     * @param array<mixed> $step3
     * @return void
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
