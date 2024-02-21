<?php
/**
 * Initialize a dependency injection container that implemented PSR-11 and return the container.
 */

declare(strict_types=1);
/**
 * 常量配置
 * @author xiaonian
 * @date 2024-02-18
 */
use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSourceFactory;

$container = new Container((new DefinitionSourceFactory())());

return ApplicationContext::setContainer($container);
