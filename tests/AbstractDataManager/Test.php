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

namespace Qunity\UnitTest\AbstractDataManager;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Qunity\DataManagerInterface;

/**
 * Class Test
 * @package Qunity\UnitTest\AbstractDataManager
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Test extends TestCase
{
    use Provider;

    /**
     * @param mixed $expected
     * @param DataManagerInterface $dataManager
     * @return void
     * @dataProvider providerGetIterator
     */
    public function testGetIterator(mixed $expected, DataManagerInterface $dataManager)
    {
        $this->assertEquals($expected, $dataManager->get());
        $this->assertEquals(new ArrayIterator($expected), $dataManager->getIterator());
        foreach ($dataManager as $id => $value) {
            $this->assertEquals($expected[$id], $value);
            $this->assertEquals(new ArrayIterator($value), $dataManager->getIterator($id));
        }
    }

    /**
     * @param mixed $id
     * @param DataManagerInterface $dataManager
     * @param mixed $value
     * @return void
     * @dataProvider providerArrayAccess
     */
    public function testArrayAccess(mixed $id, DataManagerInterface $dataManager, mixed $value)
    {
        $this->assertFalse(isset($dataManager[$id]));
        $this->assertNull($dataManager[$id]);
        $dataManager[$id] = $value;

        $this->assertTrue(isset($dataManager[$id]));
        $this->assertEquals($value, $dataManager[$id]);

        unset($dataManager[$id]);
        $this->assertFalse(isset($dataManager[$id]));
        $this->assertNull($dataManager[$id]);
    }

    /**
     * @param mixed $expected
     * @param DataManagerInterface $dataManager
     * @param mixed $method
     * @param mixed ...$args
     * @return void
     * @dataProvider providerSuccessMagicMethods
     */
    public function testSuccessMagicMethods(
        mixed $expected,
        DataManagerInterface $dataManager,
        mixed $method,
        mixed ...$args
    ) {
        // @phpstan-ignore-next-line
        $this->assertEquals($expected, call_user_func_array([$dataManager, $method], $args));
    }

    /**
     * @param mixed $eException
     * @param mixed $eMessage
     * @param DataManagerInterface $dataManager
     * @param mixed $method
     * @return void
     * @dataProvider providerErrorMagicMethods
     */
    public function testErrorMagicMethods(
        mixed $eException,
        mixed $eMessage,
        DataManagerInterface $dataManager,
        mixed $method
    ) {
        $this->expectException($eException);
        $this->expectExceptionMessage($eMessage);
        call_user_func([$dataManager, $method]); // @phpstan-ignore-line
    }

    /**
     * @param DataManagerInterface $dataManager
     * @param array<string,array> $step1
     * @param array<string,array> $step2
     * @param array<string,array> $step3
     * @return void
     * @dataProvider providerSingleMethods
     */
    public function testSingleMethods(DataManagerInterface $dataManager, array $step1, array $step2, array $step3)
    {
        list('id' => $id, 'value' => $value) = $step1;

        $this->assertFalse($dataManager->has($id));
        $this->assertNull($dataManager->get($id));
        $this->assertEquals('default', $dataManager->get($id, 'default'));
        $this->assertFalse($dataManager->try($id));
        $this->assertFalse($dataManager->try($id, fn($value) => empty($value)));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->set($id, $value));

        $this->assertTrue($dataManager->has($id));
        $this->assertEquals($value, $dataManager->get($id));
        $this->assertEquals($value, $dataManager->get($id, 'default'));
        $this->assertTrue($dataManager->try($id));
        $this->assertTrue($dataManager->try($id, fn($value) => !empty($value)));

        list('id' => $id, 'value' => $value) = $step2;

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->add($id, $value));

        list('id' => $id, 'value' => $value) = $step3;

        $this->assertTrue($dataManager->has($id));
        $this->assertEquals($value, $dataManager->get($id));
        $this->assertEquals($value, $dataManager->get($id, 'default'));
        $this->assertTrue($dataManager->try($id));
        $this->assertTrue($dataManager->try($id, fn($value) => !empty($value)));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->del($id));

        $this->assertFalse($dataManager->has($id));
        $this->assertNull($dataManager->get($id));
        $this->assertEquals('default', $dataManager->get($id, 'default'));
        $this->assertFalse($dataManager->try($id));
        $this->assertFalse($dataManager->try($id, fn($value) => empty($value)));
    }

    /**
     * @param DataManagerInterface $dataManager
     * @param array<string,array> $step1
     * @param array<string,array> $step2
     * @param array<string,array> $step3
     * @return void
     * @dataProvider providerMassMethods
     * @noinspection DuplicatedCode
     */
    public function testMassMethods(DataManagerInterface $dataManager, array $step1, array $step2, array $step3)
    {
        list('data' => $data, 'real' => $real, 'ids' => $ids, 'null' => $null, 'default' => $default) = $step1;

        $this->assertFalse($dataManager->has());
        $this->assertFalse($dataManager->has($ids));
        $this->assertEquals([], $dataManager->get());
        $this->assertEquals($null, $dataManager->get($ids));
        $this->assertEquals($default, $dataManager->get($ids, 'default'));
        $this->assertFalse($dataManager->try());
        $this->assertFalse($dataManager->try($ids));
        $this->assertFalse($dataManager->try($ids, fn($value) => empty($value)));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->set($data));

        $this->assertTrue($dataManager->has());
        $this->assertTrue($dataManager->has($ids));
        $this->assertEquals($real, $dataManager->get());
        $this->assertEquals($real, $dataManager->get($ids));
        $this->assertEquals($real, $dataManager->get($ids, 'default'));
        $this->assertTrue($dataManager->try());
        $this->assertTrue($dataManager->try($ids));
        $this->assertTrue($dataManager->try($ids, fn($value) => !empty($value)));

        list('data' => $data, 'real' => $real, 'ids' => $ids, 'null' => $null, 'default' => $default) = $step2;

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->add($data));

        $this->assertTrue($dataManager->has());
        $this->assertTrue($dataManager->has($ids));
        $this->assertEquals($real, $dataManager->get());
        $this->assertEquals($null, $dataManager->get($ids));
        $this->assertEquals($default, $dataManager->get($ids, 'default'));
        $this->assertTrue($dataManager->try());
        $this->assertTrue($dataManager->try($ids));
        $this->assertTrue($dataManager->try($ids, fn($value) => !empty($value)));

        list('data' => $data, 'real' => $real, 'ids' => $ids, 'null' => $null, 'default' => $default) = $step3;
        unset($data);

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->del($ids));

        $this->assertTrue($dataManager->has());
        $this->assertFalse($dataManager->has($ids));
        $this->assertEquals($real, $dataManager->get());
        $this->assertEquals($null, $dataManager->get($ids));
        $this->assertEquals($default, $dataManager->get($ids, 'default'));
        $this->assertTrue($dataManager->try());
        $this->assertFalse($dataManager->try($ids));
        $this->assertFalse($dataManager->try($ids, fn($value) => !empty($value)));

        $this->assertInstanceOf(DataManagerInterface::class, $dataManager->del());

        list('data' => $data, 'real' => $real, 'ids' => $ids, 'null' => $null, 'default' => $default) = $step1;
        unset($data, $real);

        $this->assertFalse($dataManager->has());
        $this->assertFalse($dataManager->has($ids));
        $this->assertEquals([], $dataManager->get());
        $this->assertEquals($null, $dataManager->get($ids));
        $this->assertEquals($default, $dataManager->get($ids, 'default'));
        $this->assertFalse($dataManager->try());
        $this->assertFalse($dataManager->try($ids));
        $this->assertFalse($dataManager->try($ids, fn($value) => empty($value)));
    }
}
