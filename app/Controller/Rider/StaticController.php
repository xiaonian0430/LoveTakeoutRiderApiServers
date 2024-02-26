<?php

declare(strict_types=1);
/**
 * 统计控制器
 * @author xiaonian
 * @date 2024-02-21
 */

namespace App\Controller\Rider;
use App\Constants\MessageCode;
use App\Controller\BaseController;
use App\Service\Member\BalanceService;
use App\Service\Rider\TimeLineService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class StaticController extends BaseController
{
    /**
     * 获取今日统计汇总数据
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function today(ResponseInterface $response): Psr7ResponseInterface
    {
        $riderId=618772238547554305;
        $todayDate=date('Ymd');
        $input=[
            'rider_id'=>$riderId,
            'today_date'=>$todayDate,
        ];
        $staticResult=(new BalanceService())->today($input);
        if($staticResult){
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage($messageCode);
            $data=[
                "amount"=>$staticResult['amount'],
                "order_num"=>$staticResult['order_num'],
            ];
        }else{
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::STATIC_TODAY_FAIL);
            $data=[
                "amount"=>0,
                "order_num"=>0,
            ];
        }
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 每日结算订单列表
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function listForOrder(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId='618772238547554305';

        //日期格式，2024-01-26
        $startDate = $this->request->input('start_date', 0);
        $endDate = $this->request->input('end_date', 0);
        $pageNo = $this->request->input('page_no', 0);
        $pageSize = $this->request->input('page_size', 0);

        $startDate=date('Ymd',strtotime($startDate));
        $endDate=date('Ymd',strtotime($endDate));
        $input=[
            'rider_id'=>$riderId,
            'start_date'=>$startDate,
            'end_date'=>$endDate,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $result=(new BalanceService())->listForOrder($input);
        $data=[
            'list'=>$result['list'],
            'role_type'=>$result['role_type'],
            'total'=>$result['count'],
        ];
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 工作时间记录列表
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function listForTime(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId='618772238547554305';
        $siteId='2';

        //日期格式，2024-01-26
        $startDate = $this->request->input('start_date', 0);
        $endDate = $this->request->input('end_date', 0);
        $pageNo = $this->request->input('page_no', 0);
        $pageSize = $this->request->input('page_size', 0);

        $startDate=date('Ymd',strtotime($startDate));
        $endDate=date('Ymd',strtotime($endDate));
        $input=[
            'rider_id'=>$riderId,
            'site_id'=>$siteId,
            'start_date'=>$startDate,
            'end_date'=>$endDate,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $list=(new TimeLineService())->list($input);
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
}
