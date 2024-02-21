<?php

declare(strict_types=1);
/**
 * 测试控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller;

class IndexController extends AbstractController
{
    public function index()
    {
        $user = $this->request->input('user', 'Index');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function test()
    {
        $user = $this->request->input('user', 'Test');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
