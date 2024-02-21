<?php

declare(strict_types=1);
/**
 * 监听配置
 * @author xiaonian
 * @date 2024-02-18
 */
return [
    Hyperf\ExceptionHandler\Listener\ErrorExceptionHandler::class,
    Hyperf\Command\Listener\FailToHandleListener::class,
];
