<?php

namespace App\Service\Order;
use App\Model\Delivery\BoxOrder;
use Hyperf\DbConnection\Db;
use App\Model\Takeout\Order;
use App\Model\Takeout\OrderDetail;
use App\Model\Delivery\Order as DeliveryOrder;
use App\Model\Takeout\Store;

class OrderService{
    public function list($type,$page,$pageSize)
    {
        if($type==1){
            //新订单
            $orderStatus=1;
        }else if($type==2){
            $orderStatus=2;
        }else{
            $orderStatus=3;
        }
        $offset=($page-1)*$pageSize;
        $query='SELECT a.user_address_info,a.order_no,b.title,a.order_time FROM `delivery_order` as a left join `lt_store` as b on b.store_id=a.store_id where a.order_status=1 limit 0,10';
        $result=Db::select($query);
        return $result;
    }

    public function detail($orderNo)
    {
        $orderCols=[
            'address',
            'store_id',
            'price',
            'o_price',
            'pay_time',
            'remarks',
        ];
        $storeCols=[
            'province',
            'city',
            'district',
            'address',
            'title',
        ];
        $orderDetailCols=[
            'goods_no',
            'goods_title',
            'goods_num',
            'goods_price',
        ];
        $orderInfo=Order::query()->where('order_no',$orderNo)->first($orderCols);
        if(!empty($orderInfo)){
            $storeId=$orderInfo['store_id'];
            $storeInfo=Store::query()->where('store_id',$storeId)->first($storeCols);
            $orderDetailInfo=OrderDetail::query()->where('order_no',$orderNo)->get($orderDetailCols);
            $data=[
                'address'=>$orderInfo['address'],
                'store_id'=>$orderInfo['store_id'],
                'price'=>$orderInfo['price'],
                'o_price'=>$orderInfo['o_price'],
                'pay_time'=>date('Y-m-d H:i:s',$orderInfo['pay_time']),
                'remarks'=>$orderInfo['remarks'],
                'store_info'=>$storeInfo,
                'order_detail'=>$orderDetailInfo
            ];
        }else{
            $data=[];
        }
        return $data;
    }

    /*
     * 获取盒子订单列表
     */
    public function listForBox($input)
    {
        $offset=($input['page_no']-1)*$input['page_size'];
        $cols=[
            'c.box_order_id',
            'a.user_address_info',
            'a.order_no',
            'b.title',
            'a.pay_time'
        ];
        $result = Db::table(BoxOrder::TABLE_NAME.' as c')
            ->leftJoin(DeliveryOrder::TABLE_NAME.' as a', 'a.order_no', '=', 'c.order_no')
            ->leftJoin(Store::TABLE_NAME.' as b', 'b.store_id', '=', 'a.store_id')
            ->where('c.box_id',$input['box_id'])
            ->where('c.status',1)
            ->orderBy('c.box_order_id', 'desc')
            ->offset($offset)
            ->limit($input['page_size'])
            ->get($cols);
        $orderCount=$this->getOrderCount(['box_id'=>$input['box_id']]);
        if(empty($result)){
            $result=[];
        }
        $data=[
            'list'=>$result,
            'count'=>$orderCount,
        ];
        return $data;
    }

    /**
     * 盒子订单数量
     * @param $input
     * @return int
     */
    public function getOrderCount($input)
    {
        $count=BoxOrder::query()
            ->where('box_id',$input['box_id'])
            ->where('status',1)
            ->count();
        return $count;
    }

    /**
     * 根据订单号，手机号，流水号，获取所属站点下面的订单信息
     * @param $input
     * @return mixed
     */
    public function detailBySearch($input)
    {
        //search_type: 1表示订单号搜索，2表示取餐号搜索，3表示手机尾号后四位搜索
        $cols=[
            'a.order_no',
            'a.print_number',
            'a.user_mobile',
            'a.user_mobile_last',
            'a.user_address_info',
            'b.title',
            'a.pay_time'
        ];
        $model = Db::table(DeliveryOrder::TABLE_NAME.' as a')
            ->leftJoin(Store::TABLE_NAME.' as b', 'b.store_id', '=', 'a.store_id')
            ->offset(0)
            ->limit(10);
        if($input['search_type']==1){
            //1表示订单号搜索
            $model->where('a.order_no',$input['search_key']);
        }else if($input['search_type']==2){
            //2表示取餐号搜索
            $model->where('a.print_number',$input['search_key']);
        }else if($input['search_type']==3){
            //3表示手机尾号后四位搜索
            $model->where('a.user_mobile_last',$input['search_key']);
        }else{
            return false;
        }
        $result=$model->get($cols);
        if(empty($result)){
            $result=[];
        }
        return $result;
    }

    /**
     * 收餐-加入盒子
     * 需要做判断
     * （1）当该订单暂未收餐，执行收餐流程，订单状态由待取餐，转变为配送中
     * （2）当该订单已收餐，执行自动从A盒子转移到B盒子流程，订单状态不变
     */
    public function orderReceive($input){
        $boxOrderId = $this->generateId();
        if(!$boxOrderId){
            return false;
        }
        $time=time();
        $insertData=[
            'box_order_id'=>$boxOrderId,
            'box_id'=>$input['box_id'],
            'd_order_id'=>$input['d_order_id'],
            'order_no'=>$input['order_no'],
            'status'=>1,
            'create_time'=>$time,
            'update_time'=>$time
        ];
        try {
            $inserted=BoxOrder::insert($insertData);
        } catch (\Exception $e) {
            $inserted=false;
        }
        if($inserted){
            $updateDOrder=[
                'update_time'=>$time,
                'receive_time'=>$time,
                'order_status'=>2,
            ];
            DeliveryOrder::query()
                ->where('d_order_id',$input['d_order_id'])
                ->update($updateDOrder);

            //操作日志
            $logData=[
                'type'=>1,
                'from_box_id'=>$input['from_box_id'],
                'from_rider_id'=>$input['from_rider_id'],
                'to_box_id'=>$input['from_rider_id'],
                'to_rider_id'=>$input['from_rider_id'],
                'operation_id'=>$input['operation_id'],
                'd_order_id'=>$input['d_order_id'],
                'order_no'=>$input['order_no'],
                'time'=>$time
            ];
            (new OrderLogService())->add($logData);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 完成盒子中的订单
     */
    public function orderFinish($input){
        $time=time();
        $updateData=[
            'status'=>3,
            'update_time'=>$time
        ];
        try {
            $model=BoxOrder::query();
            $model->where('box_order_id',$input['box_order_id']);
            $updated=$model->update($updateData);
        } catch (\Exception $e) {
            $updated=false;
        }
        if($updated){
            $updateDOrder=[
                'update_time'=>$time,
                'finish_time'=>$time,
                'order_status'=>3,
            ];
            DeliveryOrder::query()
                ->where('d_order_id',$input['d_order_id'])
                ->update($updateDOrder);

            //操作日志
            $logData=[
                'type'=>3,
                'from_box_id'=>$input['from_box_id'],
                'from_rider_id'=>$input['from_rider_id'],
                'to_box_id'=>0,
                'to_rider_id'=>0,
                'operation_id'=>$input['operation_id'],
                'd_order_id'=>$input['d_order_id'],
                'order_no'=>$input['order_no'],
                'time'=>$time
            ];
            (new OrderLogService())->add($logData);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 重回订单池
     */
    public function orderBack($input){
        $time=time();
        $updateData=[
            'status'=>4,
            'update_time'=>$time
        ];
        try {
            $model=BoxOrder::query();
            $model->where('box_order_id',$input['box_order_id']);
            $updated=$model->update($updateData);
        } catch (\Exception $e) {
            $updated=false;
        }
        if($updated){
            $updateDOrder=[
                'update_time'=>$time,
                'order_status'=>1,
            ];
            DeliveryOrder::query()
                ->where('d_order_id',$input['d_order_id'])
                ->update($updateDOrder);

            //操作日志
            $logData=[
                'type'=>4,
                'from_box_id'=>$input['from_box_id'],
                'from_rider_id'=>$input['from_rider_id'],
                'to_box_id'=>0,
                'to_rider_id'=>0,
                'operation_id'=>$input['operation_id'],
                'd_order_id'=>$input['d_order_id'],
                'order_no'=>$input['order_no'],
                'time'=>$time
            ];
            (new OrderLogService())->add($logData);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 订单转单，
     */
    public function orderTrans($input){
        $riderId='618772238547554305';
        $boxOrderId = $this->generateId();
        if(!$boxOrderId){
            return false;
        }
        $time=time();
        $updateData=[
            'status'=>2,
            'update_time'=>$time
        ];
        try {
            $model=BoxOrder::query();
            $model->where('box_order_id',$input['box_order_id']);
            $updated=$model->update($updateData);
        } catch (\Exception $e) {
            $updated=false;
        }
        if($updated){
            $insertData=[
                'box_order_id'=>$boxOrderId,
                'box_id'=>$input['to_box_id'],
                'd_order_id'=>$input['d_order_id'],
                'order_no'=>$input['order_no'],
                'status'=>1,
                'create_time'=>$time,
                'update_time'=>$time
            ];
            BoxOrder::insert($insertData);

            //操作日志
            $logData=[
                'type'=>2,
                'from_box_id'=>$input['from_box_id'],
                'from_rider_id'=>$input['from_rider_id'],
                'to_box_id'=>$input['to_box_id'],
                'to_rider_id'=>$input['to_rider_id'],
                'operation_id'=>$riderId,
                'd_order_id'=>$input['d_order_id'],
                'order_no'=>$input['order_no'],
                'time'=>$time
            ];
            (new OrderLogService())->add($logData);
            return true;
        }else{
            return false;
        }
    }
}