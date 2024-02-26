<?php

declare(strict_types=1);

/**
 * 路由器配置
 * @author xiaonian
 * @date 2024-02-18
 */
use Hyperf\HttpServer\Router\Router;
use \App\Middleware\JwtAuthMiddleware;

Router::get('/favicon.ico', function () {
    return '';
});

//用户模块
Router::addRoute(['POST'], '/api/rider/sso/passwordLogin', 'App\Controller\Rider\SsoController@passwordLogin');
Router::addRoute(['POST'], '/api/rider/sso/smsCodeLogin', 'App\Controller\Rider\SsoController@smsCodeLogin');
Router::addRoute(['POST'], '/api/rider/sso/getSmsCode', 'App\Controller\Rider\SsoController@getSmsCode');
Router::addRoute(['POST'], '/api/rider/register', 'App\Controller\Rider\RiderController@register');
Router::addRoute(['POST'], '/api/rider/box/add', 'App\Controller\Rider\BoxController@add',['middleware' => [JwtAuthMiddleware::class]]);
Router::addRoute(['POST'], '/api/rider/box/edit', 'App\Controller\Rider\BoxController@edit',['middleware' => [JwtAuthMiddleware::class]]);
Router::addRoute(['POST'], '/api/rider/box/delete', 'App\Controller\Rider\BoxController@delete',['middleware' => [JwtAuthMiddleware::class]]);
Router::addRoute(['GET'], '/api/rider/box/list', 'App\Controller\Rider\BoxController@list',['middleware' => [JwtAuthMiddleware::class]]);
Router::addRoute(['GET'], '/api/rider/box/listForOthers', 'App\Controller\Rider\BoxController@listForOthers',['middleware' => [JwtAuthMiddleware::class]]);
Router::addRoute(['GET'], '/api/member/auth/code2', 'App\Controller\Member\AuthController@getCode',['middleware' => [JwtAuthMiddleware::class]]);

