<?php

declare(strict_types=1);
/**
 * 余额控制器
 * @author xiaonian
 * @date 2024-02-21
 */

namespace App\Controller\Rider;
use App\Constants\MessageCode;
use App\Controller\BaseController;
use App\Service\Member\BalanceService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class BalanceController extends BaseController
{
    /**
     * 获取余额
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function balance(ResponseInterface $response): Psr7ResponseInterface
    {
        $riderId=618772238547554305;
        $input=[
            'rider_id'=>$riderId,
        ];
        $balance=(new BalanceService())->balance($input);
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage($messageCode);
        $data=[
            "balance"=>$balance,
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 获取余额明细列表
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function list(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId=618772238547554305;
        $pageNo = $this->request->input('page_no', 0);
        $pageSize = $this->request->input('page_size', 0);
        $input=[
            'rider_id'=>$riderId,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $result=(new BalanceService())->list($input);
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
     * 申请提现
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function withdrawal(ResponseInterface $response): Psr7ResponseInterface
    {
        $riderId=618772238547554305;
        $amount = doubleval($this->request->input('amount', 0));
        $amount=round($amount*100,0);
        if($amount<=0){
            $messageCode=MessageCode::WITHDRAWAL_ZERO_ERROR;
            $message=MessageCode::WITHDRAWAL_ZERO_ERROR;
            $isWithdrawal=0;
        }else if($amount<10000){
            $messageCode=MessageCode::WITHDRAWAL_MIN_ERROR;
            $message=MessageCode::WITHDRAWAL_MIN_ERROR;
            $isWithdrawal=0;
        }else{
            $input=[
                'rider_id'=>$riderId,
                'amount'=>$amount,
            ];
            $result=(new BalanceService())->withdrawal($input);
            if($result==1){
                $isWithdrawal=1;
                $messageCode=MessageCode::DATA_OK;
                $message=MessageCode::getMessage(MessageCode::DATA_OK);
            }else if($result==2){
                $isWithdrawal=0;
                $messageCode=MessageCode::DATA_OK;
                $message=MessageCode::getMessage(MessageCode::WITHDRAWAL_NO_ENOUGH_FAIL);
            }else{
                $isWithdrawal=0;
                $messageCode=MessageCode::WITHDRAWAL_APP_FAIL;
                $message=MessageCode::getMessage(MessageCode::WITHDRAWAL_APP_FAIL);
            }
        }
        $data=[
            "is_withdrawal"=>$isWithdrawal,
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }
}
