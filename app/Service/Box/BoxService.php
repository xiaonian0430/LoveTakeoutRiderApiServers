<?php

declare(strict_types=1);
/**
 * 盒子服务
 * @author xiaonian
 * @date 2024-02-18
 */
namespace App\Service\Box;
use App\Model\Delivery\Box;
use App\Model\Delivery\BoxOrder;
use App\Model\Delivery\Rider;
use App\Model\Delivery\Site;
use App\Service\BaseService;
use Hyperf\DbConnection\Db;

class BoxService extends BaseService {

    /*
     * 获取盒子列表
     */
    public function list($input)
    {
        $offset=($input['page_no']-1)*$input['page_size'];
        $cols=[
            'box_id',
            'title',
        ];
        try{
            $model=Box::query()
                ->where('rider_id',$input['rider_id'])
                ->where('is_delete',0);
            $result=$model
                ->orderBy('box_id', 'desc')
                ->offset($offset)
                ->limit($input['page_size'])
                ->get($cols);
            $boxIds=[];
            foreach ($result as $item){
                $boxIds[]=$item->box_id;
            }
            $boxOrders=BoxOrder::query()
                ->whereIn('box_id',$boxIds)
                ->groupBy('box_id')
                ->get(['box_id',Db::raw('COUNT(0) AS `count`')]);
            $boxOrderMap=[];
            foreach ($boxOrders as $item){
                $boxOrderMap[$item->box_id]=$item->count;
            }
            foreach ($result as $key=>$item){
                if(isset($boxOrderMap[$item->box_id])){
                    $item->order_num=$boxOrderMap[$item->box_id];
                }else{
                    $item->order_num=0;
                }
                $result[$key]=$item;
            }
            $count=$model->count();
        }catch (\Throwable $e){
            $result=[];
            $count=0;
        }
        if(empty($result)){
            $result=[];
        }
        $data=[
            'list'=>$result,
            'count'=>$count,
        ];
        return $data;
    }

    /*
     * 获取其他骑手的盒子
     */
    public function listForOthers($input)
    {
        //获取站点信息
        $siteCols=[
            'b.p_site_id',
            'a.site_id',
        ];
        $pSiteId=0;
        $siteId=0;
        $siteData=Db::table(Rider::TABLE_NAME.' as a')
            ->leftJoin(Site::TABLE_NAME.' as b','b.site_id','=','a.site_id')
            ->first($siteCols);
        if(!empty($siteData)){
            $pSiteId=$siteData['p_site_id'];
            $siteId=$siteData['site_id'];
        }

        // 获取其他站点
        if($pSiteId){
            $siteOthers=Site::query()
                ->where('p_site_id','=',$pSiteId)
                ->where('site_id','<>',$siteId)
                ->pluck('site_id');
            if(empty($siteOthers)){
                $siteOthers=[];
            }
        }else{
            $siteOthers=[];
        }

        //查询其他站点的骑手
        $offset=($input['page_no']-1)*$input['page_size'];
        $riderCols=['rider_id','name','mobile'];
        $boxCols=['rider_id','box_id','title'];
        $riderInfo=Rider::query()
            ->whereIn('site_id',$siteOthers)
            ->offset($offset)
            ->limit($input['page_size'])
            ->orderBy('rider_id', 'desc')
            ->get($riderCols);
        $riderIds=[];
        foreach ($riderInfo as $item){
            $riderIds[]=$item['rider_id'];
        }
        $boxInfo=Box::query()
            ->whereIn('rider_id',$riderIds)
            ->get($boxCols);
        $riderCount = Rider::query()
            ->whereIn('site_id',$siteOthers)
            ->count();

        //rider 盒子分块
        $riderBox=[];
        foreach($boxInfo as $item){
            $riderId=$item['rider_id'];
            unset($item['rider_id']);
            if(!isset($riderBox[$riderId])){
                $riderBox[$riderId]=[$item];
            }else{
                $riderBox[$riderId][]=$item;
            }
        }

        //合成数据
        foreach($riderInfo as $key=>$item){
            $riderId=$item['rider_id'];
            if(isset($riderBox[$riderId])){
                $item['box']=$riderBox[$riderId];
            }else{
                $item['box']=[];
            }
            $riderInfo[$key]=$item;
        }
        $data=[
            'list'=>$riderInfo,
            'count'=>$riderCount,
        ];

        return $data;
    }

    /**
     * 创建盒子
     */
    public function add($input){
        // 0=成功 1=已存在 2=失败
        $time=time();
        $boxId = $this->generateId();
        if(!$boxId){
            return [
                'code'=>2,
                'box_id'=>$boxId,
            ];
        }
        $insertData=[
            'box_id'=>$boxId,
            'rider_id'=>$input['rider_id'],
            'title'=>$input['title'],
            'create_time'=>$time,
            'update_time'=>$time,
            'is_delete'=>0,
            'is_default'=>0,
        ];
        try {
            //判断盒子名称是否已存在
            $result=Box::query()
                ->where('rider_id','=',$input['rider_id'])
                ->where('title','=',$input['title'])
                ->exists();
            if(!$result){
                $inserted=Box::insert($insertData);
                if($inserted){
                    $code=0;
                }else{
                    $code=2;
                }
            }else{
                $code=1;
            }
        } catch (\Exception $e) {
            $code=2;
        }
        return [
            'code'=>$code,
            'box_id'=>$boxId,
        ];
    }

    /**
     * 创建盒子
     * @return boolean
     */
    public function edit($input): bool
    {
        $time=time();
        $updateData=[
            'title'=>$input['title'],
            'update_time'=>$time
        ];
        $updated=Box::query()
            ->where('box_id',$input['box_id'])
            ->where('rider_id',$input['rider_id'])
            ->update($updateData);
        if($updated){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除盒子
     * @param $input
     * @return boolean
     */
    public function delete($input){
        $time=time();
        $updateData=[
            'is_delete'=>1,
            'update_time'=>$time
        ];
        $updated=Box::query()
            ->where('box_id',$input['box_id'])
            ->where('rider_id',$input['rider_id'])
            ->update($updateData);
        if($updated){
            return true;
        }else{
            return false;
        }
    }

}