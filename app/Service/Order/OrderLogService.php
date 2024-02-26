<?php

declare(strict_types=1);
/**
 * 操作服务
 * @author xiaonian
 * @date 2024-02-18
 */
namespace App\Service\Order;
use App\Model\Delivery\OrderOperationLog;
use App\Service\BaseService;

class OrderLogService extends BaseService {

    /**
     * 添加操作日志
     * @return bool
     */
    public function add($input)
    {
        $logId = $this->generateId();
        //操作日志
        $logData=[
            'log_id'=>$logId,
            'type'=>$input['type'],
            'from_box_id'=>$input['from_box_id'],
            'from_rider_id'=>$input['from_rider_id'],
            'to_box_id'=>$input['to_box_id'],
            'to_rider_id'=>$input['to_rider_id'],
            'operation_id'=>$input['operation_id'],
            'd_order_id'=>$input['d_order_id'],
            'order_no'=>$input['order_no'],
            'create_time'=>$input['time'],
            'update_time'=>$input['time']
        ];

        try {
            $insert=OrderOperationLog::insert($logData);
        } catch (\Exception $e) {
            $insert=false;
        }
        return $insert;
    }
}