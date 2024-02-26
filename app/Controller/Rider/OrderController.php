<?php

declare(strict_types=1);
/**
 * 订单控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller\Rider;

use App\Constants\MessageCode;
use App\Controller\BaseController;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use App\Service\Order\OrderService;

class OrderController extends BaseController
{
    /**
     * 配送订单列表
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function list(ResponseInterface $response) : Psr7ResponseInterface
    {
        $type = $this->request->input('type', 0);
        $pageNo = $this->request->input('page_no', 0);
        $pageSize = $this->request->input('page_size', 0);
        $list=(new OrderService())->list($type,$pageNo,$pageSize);
        $total=0;
        $data=[
            'list'=>$list,
            'total'=>$total,
        ];
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
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
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$detail,$message);
        return $response->json($json);
    }

    /**
     * 根据订单号，手机号，流水号，获取所属站点下面的订单信息
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function detailBySearch(ResponseInterface $response) : Psr7ResponseInterface
    {
        //search_type: 1表示订单搜索，2表示取餐号搜索，3表示手机尾号后四位搜索
        $searchType = $this->request->input('search_type', 0);
        $searchKey = $this->request->input('search_key', '');
        $input=[
            'search_type'=>$searchType,
            'search_key'=>$searchKey,
        ];
        $detail=(new OrderService())->detailBySearch($input);
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$detail,$message);
        return $response->json($json);
    }

    /**
     * 获取盒子订单列表
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function listForBox(ResponseInterface $response) : Psr7ResponseInterface
    {
        $boxId = $this->request->input('box_id', '0');
        $pageNo = $this->request->input('page_no', 0);
        $pageSize = $this->request->input('page_size', 0);
        $input=[
            'box_id'=>$boxId,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $result=(new OrderService())->listForBox($input);
        $data=[
            'list'=>$result['list'],
            'total'=>$result['count']
        ];
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 收餐-将订单添加到盒子中
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function orderReceive(ResponseInterface $response) : Psr7ResponseInterface
    {
        $boxId = $this->request->input('box_id', '0');
        $dOrderId = $this->request->input('d_order_id', '0');
        $input=[
            'box_id'=>$boxId,
            'd_order_id'=>$dOrderId,
        ];
        $result = (new OrderService())->orderReceive($input);
        if($result){
            $isReceive=1;
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else{
            $isReceive=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_ORDER_RECEIVE_FAIL);
        }
        $data=[
            'is_receive'=>$isReceive
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 完成订单
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function orderFinish(ResponseInterface $response) : Psr7ResponseInterface
    {
        $boxOrderId = $this->request->input('box_order_id', '0');
        $input=[
            'box_order_id'=>$boxOrderId,
        ];
        $isUpdated = (new OrderService())->orderFinish($input);
        if($isUpdated){
            $isUpdated=1;
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else{
            $isUpdated=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_ORDER_FINISH_FAIL);
        }
        $data=[
            'is_updated'=>$isUpdated
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 重回订单池
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function orderBack(ResponseInterface $response) : Psr7ResponseInterface
    {
        $boxOrderId = $this->request->input('box_order_id', '0');
        $input=[
            'box_order_id'=>$boxOrderId,
        ];
        $isBack = (new OrderService())->orderBack($input);
        if($isBack){
            $isBack=1;
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else{
            $isBack=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_ORDER_BACK_FAIL);
        }
        $data=[
            'is_back'=>$isBack
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 订单转交，来源
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function orderTrans(ResponseInterface $response) : Psr7ResponseInterface
    {
        $dOrderId = $this->request->input('d_order_id', '0');
        $orderNo = $this->request->input('order_no', '0');
        $fromRiderId = $this->request->input('from_rider_id', '0');
        $toRiderId = $this->request->input('to_rider_id', '0');
        $fromBoxId = $this->request->input('from_box_id', '0');
        $toBoxId = $this->request->input('to_box_id', '0');
        $input=[
            'd_order_id'=>$dOrderId,
            'order_no'=>$orderNo,
            'from_box_id'=>$fromBoxId,
            'to_box_id'=>$toBoxId,
            'from_rider_id'=>$fromRiderId,
            'to_rider_id'=>$toRiderId,
        ];
        $isTrans = (new OrderService())->orderTrans($input);
        if($isTrans){
            $isTrans=1;
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else{
            $isTrans=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_ORDER_TRANS_FAIL);
        }
        $data=[
            'is_trans'=>$isTrans
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }
}
