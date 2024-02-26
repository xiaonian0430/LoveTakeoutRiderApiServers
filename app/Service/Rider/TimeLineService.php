<?php

declare(strict_types=1);
/**
 * 时间线
 * @author xiaonian
 * @date 2024-02-18
 */
namespace App\Service\Rider;
use App\Constants\RedisKey;
use App\Model\Delivery\Rider;
use App\Model\Delivery\RiderTimeLine;
use App\Service\BaseService;
use Hyperf\Context\ApplicationContext;
use Hyperf\Redis\Redis;

class TimeLineService extends BaseService {

    /*
     * 获取上线，下线列表
     */
    public function list($input)
    {
        $offset=($input['page_no']-1)*$input['page_size'];
        $cols=[
            'line_day',
            'online_time',
            'offline_time'
        ];
        $model=RiderTimeLine::query()
            ->where('rider_id','=',$input['rider_id'])
            ->whereBetween('line_day',[$input['start_date'],$input['end_date']])
            ->where('site_id','=',$input['site_id']);
        $result=$model->orderBy('timeline_id', 'desc')
            ->offset($offset)
            ->limit($input['page_size'])
            ->get($cols);
        $count=$model->count();
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
     * 上线和下线操作
     * @param $input
     * @return bool
     */
    public function work($input)
    {
        $time=time();
        $today=date('Ymd',$time);
        $timeLineData=[];
        if($input['is_online']==1){
            //1=上线
            $timeLineData['online_time']=$time;
        }else{
            //0=下线
            $input['is_online']=0;
            $timeLineData['offline_time']=$time;
        }

        //先从内存中获取值，然后再落盘查询数据库
        $currentIsOnline=(new RiderService())->isOnline(['rider_id'=>$input['rider_id']]);
        if($currentIsOnline==$input['is_online']){
            //已操作，无需重复操作
            return true;
        }

        //判断今日是否有上线数据
        $timelineId=RiderTimeLine::query()
            ->where('rider_id',$input['rider_id'])
            ->where('site_id',$input['site_id'])
            ->where('line_day',$today)
            ->value('timeline_id');
        if(empty($timelineId)){
            //插入数据
            $timelineId=$this->generateId();
            if(!$timelineId){
                return false;
            }
            $timeLineData['timeline_id']=$timelineId;
            $timeLineData['rider_id']=$input['rider_id'];
            $timeLineData['site_id']=$input['site_id'];
            $timeLineData['line_day']=$today;
            $res=RiderTimeLine::query()->insert($timeLineData);
        }else{
            //更新数据
            $res=RiderTimeLine::query()
                ->where('timeline_id','=',$timelineId)
                ->update($timeLineData);
        }

        //先写DB，后删Redis
        Rider::query()
            ->where('rider_id','=',$input['rider_id'])
            ->update(['is_online'=>$input['is_online']]);
        if($res){
            $container = ApplicationContext::getContainer();
            $redis = $container->get(Redis::class);
            $key=RedisKey::RIDER_IS_ONLINE.':'.$input['rider_id'];
            try{
               $redis->del($key);
            }catch (\Throwable $e){}
            return true;
        }else{
            return false;
        }
    }
}