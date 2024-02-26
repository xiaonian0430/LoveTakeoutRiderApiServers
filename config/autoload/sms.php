<?php

declare(strict_types=1);
/**
 * 短信服务配置
 * @author xiaonian
 * @date 2024-02-18
 */
use function Hyperf\Support\env;

return [
    'host' => env('SMS_HOST', ''),
    'secret_name' => env('SMS_SECRET_NAME', ''),
    'secret_key' => env('SMS_SECRET_KEY', '')
];
