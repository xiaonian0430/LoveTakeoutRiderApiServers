<?php

declare(strict_types=1);
/**
 * 银行控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller\Rider;

use App\Constants\MessageCode;
use App\Controller\BaseController;
use App\Service\Bank\BankService;
use App\Service\Box\BoxService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class BankController extends BaseController
{
    /**
     * 获取银行
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function list(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId='618772238547554305';
        $pageNo = $this->request->input('page_no', 0);
        $pageSize = $this->request->input('page_size', 0);
        $input=[
            'rider_id'=>$riderId,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $result=(new BankService())->list($input);
        $data=[
            'list'=>$result['list'],
            'total'=>$result['count'],
        ];
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 创建
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function add(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId='618772238547554305';
        $name = $this->request->input('name', '');
        $bankTitle = $this->request->input('bank_title', '');
        $bankCard = $this->request->input('bank_card', '');
        $subBranch = $this->request->input('sub_branch', '');
        $ltBankId = $this->request->input('lt_bank_id', 0);
        $input=[
            'rider_id'=>$riderId,
            'name'=>$name,
            'bank_title'=>$bankTitle,
            'bank_card'=>$bankCard,
            'sub_branch'=>$subBranch,
            'lt_bank_id'=>$ltBankId,
        ];
        $resultAdd = (new BankService())->add($input);
        if($resultAdd){
            $isAdd=1;
            $bankId=$resultAdd['bank_id'];
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else{
            $isAdd=0;
            $bankId=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_CREATE_FAIL);
        }
        $data=[
            'is_add'=>$isAdd,
            'bank_id'=>$bankId.''
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }
}
