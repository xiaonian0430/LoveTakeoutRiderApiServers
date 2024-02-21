<?php

namespace App\Service\Order;
use App\Model\Order;
use Hyperf\DbConnection\Db;

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
}