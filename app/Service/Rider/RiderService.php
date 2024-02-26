<?php

declare(strict_types=1);
/**
 * 骑手服务
 * @author xiaonian
 * @date 2024-02-18
 */
namespace App\Service\Rider;
use App\Constants\RedisKey;
use App\Model\Delivery\Rider;
use App\Model\Delivery\RiderRole;
use App\Service\BaseService;
use Hyperf\Context\ApplicationContext;
use Hyperf\DbConnection\Db;
use Hyperf\Redis\Redis;

class RiderService extends BaseService {

    /**
     * 注册
     * @param $input
     * @return mixed
     */
    public function register($input): mixed
    {
        //0=注册成功，1已注册，2注册失败
        $riderId =0;
        $time=time();
        $input['status']=0;
        $input['over_state']=0;
        $input['is_online']=0;
        $input['is_delete']=0;
        $input['create_time']=$time;
        $input['update_time']=$time;
        try{
            $hasRiderId=Rider::query()->where('mobile','=',$input['mobile'])->value('rider_id');
            if(!$hasRiderId){
                $riderId = $this->generateId();
                if(!$riderId){
                    $riderId=0;
                    $code=2;
                }else{
                    $input['rider_id']=$riderId;
                    $res=Rider::query()->insert($input);
                    if($res){
                        $code=0;
                    }else{
                        $code=2;
                    }
                }
            }else{
                $riderId=$hasRiderId;
                $code=1;
            }
        }catch (\Throwable $e){
            $code=2;
        }
        return [
            'code'=>$code,
            'rider_id'=>$riderId
        ];
    }

    /*
     * 获取骑手列表
     */
    public function list($input)
    {
        $offset=($input['page_no']-1)*$input['page_size'];
        $cols=[
            'a.rider_id',
            'a.name',
            'a.mobile',
            'a.avatar',
            'b.role_type',
        ];
        $model=Db::table(Rider::TABLE_NAME.' as a');
        $model->where('a.site_id','=',$input['site_id']);
        if(!empty($input['name'])){
            $model->where('name','=',$input['name']);
        }
        $model->where('a.rider_id','<>',$input['rider_id']);
        $result=$model
            ->leftJoin(RiderRole::TABLE_NAME.' as b','b.rider_id','=','a.rider_id')
            ->orderBy('a.rider_id', 'desc')
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

    public function editRole($input)
    {
        $time=time();
        $updateData=[
            'role_type'=>$input['role_type'],
            'update_time'=>$time
        ];
        $updated=Rider::query()
            ->where('rider_id',$input['rider_id'])
            ->update($updateData);
        if($updated){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 将骑手移出站点
     * @param $riderId
     * @return bool
     */
    public function removeSite($input)
    {
        $time=time();
        $updateData=[
            'site_id'=>0,
            'update_time'=>$time
        ];
        $updated=Rider::query()
            ->where('rider_id',$input['rider_id'])
            ->update($updateData);
        if($updated){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $input
     * @return false|mixed|string
     */
    public function isOnline($input)
    {
        //先从内存中获取值，然后再落盘查询数据库
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);
        $key=RedisKey::RIDER_IS_ONLINE.':'.$input['rider_id'];
        try{
            $isOnline = $redis->get($key);
        }catch (\Throwable $e){
            $isOnline=false;
        }
        if($isOnline===false){
            //当前是否在线
            $isOnline=Rider::query()
                ->where('rider_id',$input['rider_id'])
                ->value('is_online');
            if(empty($isOnline)){
                $isOnline=0;
            }
            try{
                $redis->set($key,$isOnline,rand(10,30));
            }catch (\Throwable $e){}
        }
        return $isOnline;
    }

    /**
     * @param $input
     * @return false|mixed|string
     */
    public function getRoleType($input)
    {
        //先从内存中获取值，然后再落盘查询数据库
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);
        $key=RedisKey::RIDER_ROLE.':'.$input['rider_id'];
        try{
            $roleType = $redis->get($key);
        }catch (\Throwable $e){
            $roleType=false;
        }
        if($roleType===false){
            //当前是否在线
            $roleType=RiderRole::query()
                ->where('rider_id',$input['rider_id'])
                ->value('role_type');
            if(empty($roleType)){
                $roleType=0;
            }
            try{
                $redis->set($key,$roleType,rand(10,30));
            }catch (\Throwable $e){}
        }
        return $roleType;
    }

    /**
     * @param $input
     * @return false|mixed|string
     */
    public function getSiteId($input)
    {
        //先从内存中获取值，然后再落盘查询数据库
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);
        $key=RedisKey::RIDER_SITE_ID.':'.$input['rider_id'];
        try{
            $siteId = $redis->get($key);
        }catch (\Throwable $e){
            $siteId=false;
        }
        if($siteId===false){
            //当前是否在线
            $siteId=Rider::query()
                ->where('rider_id',$input['rider_id'])
                ->value('site_id');
            if(empty($siteId)){
                $siteId=0;
            }
            try{
                $redis->set($key,$siteId,rand(10,30));
            }catch (\Throwable $e){}
        }
        return $siteId;
    }
}
