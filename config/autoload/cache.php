<?php

declare(strict_types=1);
/**
 * 缓存配置
 * @author xiaonian
 * @date 2024-02-18
 */
return [
    'default' => [
        'driver' => Hyperf\Cache\Driver\RedisDriver::class,
        'packer' => Hyperf\Codec\Packer\PhpSerializerPacker::class,
        'prefix' => 'c:',
    ],
];
