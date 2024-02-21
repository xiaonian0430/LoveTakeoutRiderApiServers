<?php

declare(strict_types=1);
/**
 * 异常配置
 * @author xiaonian
 * @date 2024-02-18
 */
return [
    'handler' => [
        'http' => [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            App\Exception\Handler\AppExceptionHandler::class,
        ],
    ],
];
