<?php

declare(strict_types=1);
/**
 * 银行服务
 * @author xiaonian
 * @date 2024-02-18
 */
namespace App\Service\Bank;
use App\Model\Delivery\Bank;
use App\Service\BaseService;

class BankService extends BaseService {

    /*
     * 获取银行列表
     */
    public function list($input)
    {
        $offset=($input['page_no']-1)*$input['page_size'];
        $cols=[
            'bank_id',
            'name',
            'bank_title',
            'bank_card',
            'sub_branch',
        ];
        $result=Bank::query()
            ->where('rider_id',$input['rider_id'])
            ->orderBy('bank_id', 'desc')
            ->offset($offset)
            ->limit($input['page_size'])
            ->get($cols);
        $count=$this->getCount(['rider_id'=>$input['rider_id']]);
        if(empty($result)){
            $result=[];
            $count=0;
        }
        $data=[
            'list'=>$result,
            'count'=>$count,
        ];
        return $data;
    }

    /**
     * 获取数量
     * @param $input
     * @return int
     */
    public function getCount($input)
    {
        $count=Bank::query()
            ->where('rider_id',$input['rider_id'])
            ->count();
        return $count;
    }

    /**
     * 创建银行卡
     */
    public function add($input){
        $time=time();
        $bankId = $this->generateId();
        if(!$bankId){
            return false;
        }
        $insertData=[
            'bank_id'=>$bankId,
            'rider_id'=>$input['rider_id'],
            'name'=>$input['name'],
            'bank_title'=>$input['bank_title'],
            'bank_card'=>$input['bank_card'],
            'sub_branch'=>$input['sub_branch'],
            'lt_bank_id'=>$input['lt_bank_id'],
            'create_time'=>$time,
            'update_time'=>$time
        ];
        try {
            $inserted=Bank::insert($insertData);
        } catch (\Exception $e) {
            $inserted=false;
        }
        if($inserted){
            return [
                'bank_id'=>$bankId,
            ];
        }else{
            return false;
        }
    }
}