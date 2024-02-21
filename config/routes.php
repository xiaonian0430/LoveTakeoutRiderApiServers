<?php

declare(strict_types=1);

/**
 * 路由器配置
 * @author xiaonian
 * @date 2024-02-18
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/api/index/index', 'App\Controller\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/api/index/test', 'App\Controller\IndexController@test');
Router::get('/favicon.ico', function () {
    return '';
});


Router::addRoute(['GET', 'POST', 'HEAD'], '/api/redis/test', 'App\Controller\RedisController@test');
Router::addRoute(['GET', 'POST', 'HEAD'], '/api/mysql/test', 'App\Controller\MysqlController@test');