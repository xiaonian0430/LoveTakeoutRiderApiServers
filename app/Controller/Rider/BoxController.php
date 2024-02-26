<?php

declare(strict_types=1);
/**
 * 盒子控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller\Rider;

use App\Constants\MessageCode;
use App\Controller\BaseController;
use App\Service\Box\BoxService;
use Hyperf\Context\Context;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class BoxController extends BaseController
{
    /**
     * 获取盒子
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function list(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId=Context::get('rider_id');
        $pageNo = (int)$this->request->input('page_no', 0);
        $pageSize = (int)$this->request->input('page_size', 0);
        $input=[
            'rider_id'=>$riderId,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $result=(new BoxService())->list($input);
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
        $riderId=Context::get('rider_id');
        $title = $this->request->input('title', '');
        $input=[
            'title'=>$title,
            'rider_id'=>$riderId,
        ];
        $resultCreated = (new BoxService())->add($input);
        if($resultCreated['code']==0){
            $boxId=''.$resultCreated['box_id'];
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else if($resultCreated['code']==1){
            $boxId='0';
            $messageCode=1001;
            $message=MessageCode::getMessage(MessageCode::BOX_TITLE_EXIST);
        }else{
            $boxId='0';
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_CREATE_FAIL);
        }
        $data=[
            'box_id'=>$boxId
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 编辑
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function edit(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId=Context::get('rider_id');
        $boxId = $this->request->input('box_id', '0');
        $title = $this->request->input('title', '');
        $input=[
            'title'=>$title,
            'box_id'=>$boxId,
            'rider_id'=>$riderId,
        ];
        $resultEdited=(new BoxService())->edit($input);
        if($resultEdited){
            $edit=1;
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else{
            $edit=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_EDIT_FAIL);
        }
        $data=[
            'edit'=>$edit
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 删除
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function delete(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId=Context::get('rider_id');
        $boxId = $this->request->input('box_id', 0);
        $input=[
            'box_id'=>$boxId,
            'rider_id'=>$riderId,
        ];
        $isDeleted=(new BoxService())->delete($input);
        if($isDeleted){
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
            $delete=1;
        }else{
            $delete=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::BOX_DELETE_FAIL);

        }
        $data=[
            'delete'=>$delete
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 获取其他站点下骑手的盒子
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function listForOthers(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId=Context::get('rider_id');
        $pageNo = (int)$this->request->input('page_no', 0);
        $pageSize = (int)$this->request->input('page_size', 0);
        $input=[
            'rider_id'=>$riderId,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $result=(new BoxService())->listForOthers($input);
        $data=[
            'list'=>$result['list'],
            'total'=>$result['count'],
        ];
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }
}
