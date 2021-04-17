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

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Qunity\Component\DataManagerInterface;

/**
 * Class AbstractTest
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
abstract class AbstractTest extends TestCase
{
    use AbstractProvider;

    /**
     * @param mixed $expected
     * @param mixed $dataManager
     * @return void
     * @dataProvider providerGetIterator
     */
    public function testGetIterator(mixed $expected, mixed $dataManager)
    {
        $this->assertEquals(new ArrayIterator($expected), $dataManager->getIterator());
        foreach ($dataManager as $key => $item) {
            $this->assertEquals($expected[$key], $item);
        }
        $this->assertEquals($expected, $dataManager->get());
    }

    /**
     * @param mixed $path
     * @param mixed $dataManager
     * @param mixed $value
     * @return void
     * @dataProvider providerArrayAccess
     */
    public function testArrayAccess(mixed $path, mixed $dataManager, mixed $value)
    {
        $this->assertFalse(isset($dataManager[$path]));
        $this->assertNull($dataManager[$path]);
        $dataManager[$path] = $value;

        $this->assertTrue(isset($dataManager[$path]));
        $this->assertEquals($value, $dataManager[$path]);

        unset($dataManager[$path]);
        $this->assertFalse(isset($dataManager[$path]));
        $this->assertNull($dataManager[$path]);
    }

    /**
     * @param mixed $expected
     * @param mixed $dataManager
     * @param mixed $method
     * @param mixed ...$args
     * @return void
     * @dataProvider providerSuccessMagicMethods
     */
    public function testSuccessMagicMethods(mixed $expected, mixed $dataManager, mixed $method, mixed ...$args)
    {
        // @phpstan-ignore-next-line
        $this->assertEquals($expected, call_user_func_array([$dataManager, $method], $args));
    }

    /**
     * @param mixed $expectedException
     * @param mixed $expectedMessage
     * @param mixed $dataManager
     * @param mixed $method
     * @return void
     * @dataProvider providerErrorMagicMethods
     */
    public function testErrorMagicMethods(
        mixed $expectedException,
        mixed $expectedMessage,
        mixed $dataManager,
        mixed $method
    ) {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedMessage);
        call_user_func([$dataManager, $method]); // @phpstan-ignore-line
    }

    /**
     * @param mixed $dataManager
     * @param array<string,array> $step1
     * @param array<string,array> $step2
     * @param array<string,array> $step3
     * @return void
     * @dataProvider providerSingleMethods
     */
    public function testSingleMethods(mixed $dataManager, array $step1, array $step2, array $step3)
    {
        list('path' => $path, 'value' => $value) = $step1;

        $this->assertFalse($dataManager->has($path));
        $this->assertNull($dataManager->get($path));
        $this->assertEquals('default', $dataManager->get($path, 'default'));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->set($path, $value));

        $this->assertTrue($dataManager->has($path));
        $this->assertEquals($value, $dataManager->get($path));
        $this->assertEquals($value, $dataManager->get($path, 'default'));

        list('path' => $path, 'value' => $value) = $step2;

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->add($path, $value));

        list('path' => $path, 'value' => $value) = $step3;

        $this->assertTrue($dataManager->has($path));
        $this->assertEquals($value, $dataManager->get($path));
        $this->assertEquals($value, $dataManager->get($path, 'default'));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->del($path));

        $this->assertFalse($dataManager->has($path));
        $this->assertNull($dataManager->get($path));
        $this->assertEquals('default', $dataManager->get($path, 'default'));
    }

    /**
     * @param mixed $dataManager
     * @param array<string,array> $step1
     * @param array<string,array> $step2
     * @param array<string,array> $step3
     * @return void
     * @dataProvider providerMassMethods
     * @noinspection DuplicatedCode
     */
    public function testMassMethods(mixed $dataManager, array $step1, array $step2, array $step3)
    {
        list('data' => $data, 'real' => $real, 'paths' => $paths, 'null' => $null, 'default' => $default) = $step1;

        $this->assertFalse($dataManager->has());
        $this->assertFalse($dataManager->has($paths));
        $this->assertEquals([], $dataManager->get());
        $this->assertEquals($null, $dataManager->get($paths));
        $this->assertEquals($default, $dataManager->get($paths, 'default'));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->set($data));

        $this->assertTrue($dataManager->has());
        $this->assertTrue($dataManager->has($paths));
        $this->assertEquals($real, $dataManager->get());
        $this->assertEquals($real, $dataManager->get($paths));
        $this->assertEquals($real, $dataManager->get($paths, 'default'));

        list('data' => $data, 'real' => $real, 'paths' => $paths, 'null' => $null, 'default' => $default) = $step2;

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->add($data));

        $this->assertTrue($dataManager->has());
        $this->assertTrue($dataManager->has($paths));
        $this->assertEquals($real, $dataManager->get());
        $this->assertEquals($null, $dataManager->get($paths));
        $this->assertEquals($default, $dataManager->get($paths, 'default'));

        list('data' => $data, 'real' => $real, 'paths' => $paths, 'null' => $null, 'default' => $default) = $step3;
        unset($data);

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->del($paths));

        $this->assertTrue($dataManager->has());
        $this->assertFalse($dataManager->has($paths));
        $this->assertEquals($real, $dataManager->get());
        $this->assertEquals($null, $dataManager->get($paths));
        $this->assertEquals($default, $dataManager->get($paths, 'default'));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->del());

        list('data' => $data, 'real' => $real, 'paths' => $paths, 'null' => $null, 'default' => $default) = $step1;
        unset($data, $real);

        $this->assertFalse($dataManager->has());
        $this->assertFalse($dataManager->has($paths));
        $this->assertEquals([], $dataManager->get());
        $this->assertEquals($null, $dataManager->get($paths));
        $this->assertEquals($default, $dataManager->get($paths, 'default'));
    }
}
