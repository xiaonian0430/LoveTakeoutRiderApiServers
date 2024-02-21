<?php

declare(strict_types=1);
/**
 * 测试控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller;
use Hyperf\DbConnection\Db;

class MysqlController extends AbstractController
{

    public function test()
    {
        Db::table('users')->insert([
            ['email' => 'taylor@example.com', 'votes' => 0],
            ['email' => 'dayle@example.com', 'votes' => 0]
        ]);

        Db::table('users')->where('id', 1)->update(['votes' => 1]);

        return [
            'redis_key' => 'ok',
            'message' => "Test Mysql.",
        ];
    }
}
