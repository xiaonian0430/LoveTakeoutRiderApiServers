<?php

declare(strict_types=1);
/**
 * 订单控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller\Rider;

use App\Controller\AbstractController;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use App\Service\Order\OrderService;

class OrderController extends AbstractController
{
    /**
     * 配送订单列表
     * @return array
     */
    public function list(ResponseInterface $response) : Psr7ResponseInterface
    {
        $type = $this->request->input('type', 0);
        $page = $this->request->input('page', 0);
        $pageSize = $this->request->input('page_size', 0);
        $list=(new OrderService())->list($type,$page,$pageSize);
        $jsonData=[
            'code'=>200,
            'message'=>'ok',
            'data'=>[
                'list'=>$list,
                'page'=>$page,
                'page_size'=>$pageSize,
            ],
        ];
        return $response->json($jsonData);
    }

    /**
     * 详情
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function detail(ResponseInterface $response) : Psr7ResponseInterface
    {
        $orderNo = $this->request->input('order_no', '');
        $detail=(new OrderService())->detail($orderNo);
        $jsonData=[
            'code'=>200,
            'message'=>'ok',
            'data'=>$detail,
        ];
        return $response->json($jsonData);
    }
}
