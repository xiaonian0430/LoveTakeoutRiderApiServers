<?php

namespace App\Service\Order;
use Hyperf\DbConnection\Db;
use App\Model\Takeout\Order;
use App\Model\Takeout\OrderDetail;
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
}