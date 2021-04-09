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

/**
 * Trait Helper
 * @package Qunity\UnitTest\Component\AbstractDataManager
 */
trait Helper
{
    /**
     * Prepare step data for mass methods
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     *
     * @param array $step
     * @return array
     */
    protected function stepMassMethods(array $step): array
    {
        list('data' => $data, 'flat' => $flat, 'real' => $real) = $step;
        $paths = array_keys($data);
        return [
            'data' => $data, 'flat' => $flat, 'real' => $real, 'paths' => $paths,
            'dataNull' => array_fill_keys($paths, null),
            'dataDefault' => array_fill_keys($paths, 'default'),
            'pathsDefault' => array_map(function (int|string $path): array {
                return ['path' => $path, 'default' => 'default'];
            }, $paths)
        ];
    }
}
