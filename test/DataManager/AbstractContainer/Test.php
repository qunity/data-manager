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

namespace Qunity\UnitTest\Component\DataManager\AbstractContainer;

use Exception;
use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManager\ContainerFactory;
use Qunity\Component\DataManager\ContainerInterface;

/**
 * Class Test
 * @package Qunity\UnitTest\Component\DataManager\AbstractContainer
 */
class Test extends TestCase
{
    use Provider;
    use Helper;

    /**
     * @param mixed $expected
     * @param ContainerInterface $container
     * @throws Exception
     * @dataProvider providerGetIterator
     */
    public function testGetIterator(mixed $expected, ContainerInterface $container)
    {
        $this->assertEquals($expected, $container->getIterator());
    }

    /**
     * @param string|int $path
     * @param mixed $value
     * @dataProvider providerArrayAccess
     */
    public function testArrayAccess(string|int $path, mixed $value)
    {
        $object = ContainerFactory::create();

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
     * @param array $step1
     * @param array $step2
     * @param array $step3
     * @dataProvider providerSingleMethods
     */
    public function testSingleMethods(array $step1, array $step2, array $step3)
    {
        $object = ContainerFactory::create();

        list($path, $value) = $step1;

        $this->assertFalse($object->hasElement($path));
        $this->assertNull($object->getElement($path));
        $this->assertEquals('default', $object->getElement($path, 'default'));

        $this->assertInstanceOf(ContainerInterface::class, $object->setElement($path, $value));

        $this->assertTrue($object->hasElement($path));
        $this->assertEquals($value, $object->getElement($path));
        $this->assertEquals($value, $object->getElement($path, 'default'));

        list($path, $value) = $step2;

        $this->assertInstanceOf(ContainerInterface::class, $object->addElement($path, $value));

        list($path, $value) = $step3;

        $this->assertTrue($object->hasElement($path));
        $this->assertEquals($value, $object->getElement($path));
        $this->assertEquals($value, $object->getElement($path, 'default'));

        $this->assertInstanceOf(ContainerInterface::class, $object->delElement($path));

        $this->assertFalse($object->hasElement($path));
        $this->assertNull($object->getElement($path));
        $this->assertEquals('default', $object->getElement($path, 'default'));
    }

    /**
     * @param array $step1
     * @param array $step2
     * @param array $step3
     * @dataProvider providerMassMethods
     */
    public function testMassMethods(array $step1, array $step2, array $step3)
    {
        $object = ContainerFactory::create();

        list(
            'data' => $data,
            'flat' => $flat,
            'real' => $real,
            'paths' => $paths,
            'dataNull' => $dataNull,
            'dataDefault' => $dataDefault,
            'pathsDefault' => $pathsDefault
            ) = $this->stepMassMethods($step1);

        $this->assertFalse($object->hasElements());
        $this->assertFalse($object->hasElements($paths));
        $this->assertEquals([], $object->getElements());
        $this->assertEquals($dataNull, $object->getElements($paths));
        $this->assertEquals($dataDefault, $object->getElements($pathsDefault));

        $this->assertInstanceOf(ContainerInterface::class, $object->setElements($data));

        $this->assertTrue($object->hasElements());
        $this->assertTrue($object->hasElements($paths));
        $this->assertEquals($real, $object->getElements());
        $this->assertEquals($flat, $object->getElements($paths));
        $this->assertEquals($flat, $object->getElements($pathsDefault));

        list(
            'data' => $data,
            'flat' => $flat,
            'real' => $real,
            'paths' => $paths,
            'pathsDefault' => $pathsDefault
            ) = $this->stepMassMethods($step2);

        $this->assertInstanceOf(ContainerInterface::class, $object->addElements($data));

        $this->assertTrue($object->hasElements());
        $this->assertTrue($object->hasElements($paths));
        $this->assertEquals($real, $object->getElements());
        $this->assertEquals($flat, $object->getElements($paths));
        $this->assertEquals($flat, $object->getElements($pathsDefault));

        list(
            'real' => $real,
            'paths' => $paths,
            'dataNull' => $dataNull,
            'dataDefault' => $dataDefault,
            'pathsDefault' => $pathsDefault
            ) = $this->stepMassMethods($step3);

        $this->assertInstanceOf(ContainerInterface::class, $object->delElements($paths));

        $this->assertTrue($object->hasElements());
        $this->assertFalse($object->hasElements($paths));
        $this->assertEquals($real, $object->getElements());
        $this->assertEquals($dataNull, $object->getElements($paths));
        $this->assertEquals($dataDefault, $object->getElements($pathsDefault));

        $this->assertInstanceOf(ContainerInterface::class, $object->delElements());

        $this->assertFalse($object->hasElements());
        $this->assertFalse($object->hasElements($paths));
        $this->assertEquals([], $object->getElements());
        $this->assertEquals($dataNull, $object->getElements($paths));
        $this->assertEquals($dataDefault, $object->getElements($pathsDefault));
    }
}
