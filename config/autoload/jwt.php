<?php

declare(strict_types=1);
/**
 * 短信服务配置
 * @author xiaonian
 * @date 2024-02-18
 */
use function Hyperf\Support\env;

return [
    'ttl' => env('JWT_TTL', ''),
    'secret_key' => env('JWT_SECRET_KEY', ''),
    'iss' => env('JWT_ISS', '')
];
