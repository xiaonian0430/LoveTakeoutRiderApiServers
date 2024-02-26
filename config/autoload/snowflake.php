<?php

declare(strict_types=1);
/**
 * 雪花算法发号配置
 * @author xiaonian
 * @date 2024-02-18
 */
use Hyperf\Snowflake\MetaGenerator\RedisMilliSecondMetaGenerator;
use Hyperf\Snowflake\MetaGenerator\RedisSecondMetaGenerator;
use Hyperf\Snowflake\MetaGeneratorInterface;

return [
    'begin_second' => MetaGeneratorInterface::DEFAULT_BEGIN_SECOND,
    RedisMilliSecondMetaGenerator::class => [
        'pool' => 'default',
        'key' => 'rider:snowflake:workerId'
    ],
    RedisSecondMetaGenerator::class => [
        'pool' => 'default',
        'key' => 'rider:snowflake:workerId'
    ],
];
