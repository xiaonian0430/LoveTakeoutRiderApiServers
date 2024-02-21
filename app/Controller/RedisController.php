<?php

declare(strict_types=1);
/**
 * 测试控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller;
use Hyperf\Redis\Redis;
use Hyperf\Di\Annotation\Inject;

class RedisController extends AbstractController
{
    #[Inject]
    protected Redis $redis;

    public function test()
    {
        $this->redis->set('redis_key', '王美丽');

        $name=$this->redis->get('redis_key');

        return [
            'redis_key' => $name,
            'message' => "Test Redis.",
        ];
    }
}
