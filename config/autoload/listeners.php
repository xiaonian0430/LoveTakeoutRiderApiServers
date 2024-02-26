<?php

declare(strict_types=1);
/**
 * 注册该监听器
 * @author xiaonian
 * @date 2024-02-18
 */


return [
    Hyperf\ExceptionHandler\Listener\ErrorExceptionHandler::class,
    Hyperf\Command\Listener\FailToHandleListener::class,
];
