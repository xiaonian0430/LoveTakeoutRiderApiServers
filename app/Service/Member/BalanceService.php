<?php

namespace App\Service\Member;
use App\Model\Delivery\BalanceLog;
use App\Model\Delivery\Order as DeliveryOrder;
use App\Model\Delivery\Rider;
use App\Service\BaseService;
use App\Service\Rider\RiderService;
use Hyperf\DbConnection\Db;

class BalanceService extends BaseService
{
    /**
     * 今日数据
     * @param $input
     * @return mixed
     */
    public function today($input)
    {
        $model=BalanceLog::query()
            ->where('balance_day','=',$input['today_date'])
            ->where('rider_id','=',$input['rider_id'])
            ->whereIn('amount_type',[1,2]);
        $amount=$model->sum('amount');
        $orderNum=$model->count();
        if(empty($amount)) {
            $amount=0;
            $orderNum=0;
        }
        $result=[
            'amount'=>$amount,
            'order_num'=>$orderNum,
        ];
        return $result;
    }

    /**
     * 提现申请
     * @param $input
     * @return float|int 1：提现申请成功，2：提现申请失败，提现金额不能大于账户余额，3，提现申请失败
     */
    public function withdrawal($input)
    {
        $balanceId = $this->generateId();
        if(!$balanceId){
            return 3;
        }
        $balance=Rider::query()
            ->where('rider_id','=',$input['rider_id'])
            ->value('balance');
        if(empty($balance)){
            return 2;
        }else if($balance<$input['amount']){
            return 2;
        }else{
            //调用银行接口，申请提现
            $balanceRemain=$balance-$input['amount'];
            $time=time();
            $balanceDay=date('Ymd',$time);

            //组装数据
            $balanceLog = [
                'balance_id'=>$balanceId,
                'rider_id'=>$input['rider_id'],
                'balance_day'=>$balanceDay,
                'balance'=>$balanceRemain,
                'amount_type'=>3,
                'amount'=>0-$input['amount'],
                'status'=>3,
                'name'=>'',
                'bank_title'=>'',
                'bank_card'=>'',
                'sub_branch'=>'',
                'confirm_status'=>3,
                'create_time'=>$time,
                'update_time'=>$time,
            ];
            $riderBalanceData=[
                'balance'=>$balanceRemain,
                'update_time'=>$time
            ];
            Db::beginTransaction();
            try{
                $update=Db::table(Rider::TABLE_NAME)
                    ->where('rider_id','=',$input['rider_id'])
                    ->update($riderBalanceData);
                $insert=Db::table(BalanceLog::TABLE_NAME)->insert($balanceLog);
                if($insert && $update){
                    Db::commit();
                    $resultCode=1;
                }else{
                    Db::rollBack();
                    $resultCode=3;
                }
            } catch(\Throwable $ex){
                Db::rollBack();
                $resultCode=3;
            }
            return $resultCode;
        }
    }

    /**
     * 获取余额
     * @param $input
     * @return float|int
     */
    public function balance($input)
    {
        $balance=Rider::query()
            ->where('rider_id','=',$input['rider_id'])
            ->value('balance');
        if(empty($balance)){
            $balance=0;
        }
        return $balance;
    }

    /*
     * 获取余额明细列表
     */
    public function list($input)
    {
        $offset=($input['page_no']-1)*$input['page_size'];
        $cols=[
            'a.balance_id',
            'a.amount_type',
            'a.amount',
            'a.update_time',
            'a.status',
            'a.d_order_id',
            'b.store_title',
            'b.print_number',
        ];
        $result = Db::table(BalanceLog::TABLE_NAME.' as a')
            ->leftJoin(DeliveryOrder::TABLE_NAME.' as b', 'b.d_order_id', '=', 'a.d_order_id')
            ->where('a.rider_id',$input['rider_id'])
            ->orderBy('a.balance_id', 'desc')
            ->offset($offset)
            ->limit($input['page_size'])
            ->get($cols);
        $count=0;
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
     * 订单统计
     */
    public function listForOrder($input)
    {
        $offset=($input['page_no']-1)*$input['page_size'];
        $roleType=(new RiderService())->getRoleType($input);

        $bindings=[
            $input['start_date'],
            $input['end_date'],
        ];
        //1=站长，2=收餐员，3=骑手
        if($roleType==2 || $roleType==3){
            $sql='select';
            $sql.=' balance_day,count(1) as order_num,sum(amount) as amount';
            $sql.=' from delivery_balance_log where';
            $sql.=' balance_day BETWEEN ? and ?';
            $sql.=' and rider_id=?';
            $sql.=' and amount_type=?';
            $sql.=' GROUP BY balance_day ORDER BY balance_day desc';
            $sql.=' LIMIT ?,?';
            $bindings[]=$input['rider_id'];
            if($roleType==2){
                $bindings[]=1;
            }else{
                $bindings[]=2;
            }
            $bindings[]=$offset;
            $bindings[]=$input['page_size'];
        }else if($roleType==1){
            $sql='select a.balance_day,a.rider_id,b.name,count(1) as order_num';
            $sql.=' from delivery_balance_log as a';
            $sql.=' left join delivery_rider as b on b.rider_id=a.rider_id';
            $sql.=' where a.balance_day BETWEEN ? and ? and';
            $sql.=' a.site_id=? and';
            $sql.=' a.amount_type in (1,2)';
            $sql.=' GROUP BY a.balance_day,a.rider_id ORDER BY a.balance_day desc';
            $sql.=' LIMIT ?,?';

            $siteId=(new RiderService())->getSiteId($input);

            $bindings[]=$siteId;
            $bindings[]=$offset;
            $bindings[]=$input['page_size'];
        }else{
            return false;
        }

        $result=Db::select($sql,$bindings);
        $count=count($result);
        $data=[
            'list'=>$result,
            'role_type'=>$roleType,
            'count'=>$count,
        ];
        return $data;
    }
}