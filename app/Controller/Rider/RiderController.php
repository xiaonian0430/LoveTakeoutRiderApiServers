<?php

declare(strict_types=1);
/**
 * 骑手控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller\Rider;

use App\Constants\MessageCode;
use App\Controller\BaseController;
use App\Service\Rider\RiderService;
use App\Service\Rider\TimeLineService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class RiderController extends BaseController
{
    /**
     * 注册
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function register(ResponseInterface $response) : Psr7ResponseInterface
    {
        $mobile = $this->request->input('mobile', '');
        $password=88888888;
        $name = $this->request->input('name', '');
        $sex = $this->request->input('sex', 0);
        $idCardPositive = $this->request->input('id_card_positive', '');
        $idCardBack = $this->request->input('id_card_back', '');
        $idCardValidity = $this->request->input('id_card_validity', '');
        $idCardIssue = $this->request->input('id_card_issue', '');
        $idCard = $this->request->input('id_card', '');
        $nation = $this->request->input('nation', '');
        $birth = $this->request->input('birth', '');
        $address = $this->request->input('address', '');
        $healthCard = $this->request->input('health_card', '');
        $studentCard = $this->request->input('student_card', '');
        $intendedSiteId = $this->request->input('intended_site_id', 0);
        $intendedRoleType = $this->request->input('intended_role_type', 0);
        $formerlyDelivery = $this->request->input('formerly_delivery', 0);
        $experienceElectric = $this->request->input('experience_electric', 0);
        $bloodType = $this->request->input('blood_type', '');
        $medicalHistory = $this->request->input('medical_history', 0);
        $input=[
            'account'=>$mobile,
            'mobile'=>$mobile,
            'password'=>md5((string)$password),
            'name'=>$name,
            'sex'=>$sex,
            'id_card_positive'=>$idCardPositive,
            'id_card_back'=>$idCardBack,
            'id_card_validity'=>$idCardValidity,
            'id_card_issue'=>$idCardIssue,
            'nation'=>$nation,
            'id_card'=>$idCard,
            'birth'=>str_replace("-", "", $birth),
            'address'=>$address,
            'health_card'=>$healthCard,
            'student_card'=>$studentCard,
            'intended_site_id'=>$intendedSiteId,
            'intended_role_type'=>$intendedRoleType,
            'formerly_delivery'=>$formerlyDelivery,
            'experience_electric'=>$experienceElectric,
            'blood_type'=>$bloodType,
            'medical_history'=>$medicalHistory,
        ];
        $result=(new RiderService())->register($input);
        if($result['code']==0){
            $riderId=$result['rider_id'];
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else if($result['code']==1){
            $riderId=$result['rider_id'];
            $messageCode=1001;
            $message=MessageCode::getMessage(MessageCode::HAD_REGISTERED);
        }else{
            $riderId=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::REGISTER_FAIL);
        }
        $data=[
            'rider_id'=>$riderId.''
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 骑手列表
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function list(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId='618772238547554305';
        $siteId='2';
        $name = $this->request->input('name', '');
        $pageNo = $this->request->input('page_no', 0);
        $pageSize = $this->request->input('page_size', 0);
        $input=[
            'rider_id'=>$riderId,
            'site_id'=>$siteId,
            'name'=>$name,
            'page_no'=>$pageNo,
            'page_size'=>$pageSize,
        ];
        $list=(new RiderService())->list($input);
        $total=0;
        $data=[
            'list'=>$list,
            'total'=>$total,
        ];
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 编辑角色
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function editRole(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId = $this->request->input('rider_id', '0');
        $roleType = $this->request->input('role_type', '0');
        $input=[
            'rider_id'=>$riderId,
            'role_type'=>$roleType,
        ];
        $resultEdited=(new RiderService())->editRole($input);
        if($resultEdited){
            $isEdited=1;
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
        }else{
            $isEdited=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::RIDER_ROLE_EDIT_FAIL);
        }
        $data=[
            'is_edited'=>$isEdited
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 将骑手移出站点
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function removeSite(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId = $this->request->input('rider_id', '0');
        $input=[
            'rider_id'=>$riderId,
        ];
        $isRemoved=(new RiderService())->removeSite($input);
        if($isRemoved){
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
            $isRemoved=1;
        }else{
            $isRemoved=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::RIDER_DELETE_FAIL);
        }
        $data=[
            'is_removed'=>$isRemoved
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 骑手工作操作，操作上线或下线
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function work(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId = 12121;
        $siteId = 12121;

        //0=下线，1=上线
        $isOnline = $this->request->input('is_online', 0);
        $input=[
            'rider_id'=>$riderId,
            'site_id'=>$siteId,
            'is_online'=>$isOnline,
        ];
        $isDone=(new TimeLineService())->work($input);
        if($isDone){
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
            $isDone=1;
        }else{
            $isDone=0;
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::OPERATION_FAIL);
        }
        $data=[
            'is_done'=>$isDone
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }

    /**
     * 当前是否在线
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function isOnline(ResponseInterface $response) : Psr7ResponseInterface
    {
        $riderId = 12121;
        $input=[
            'rider_id'=>$riderId,
        ];
        $isOnline=(new RiderService())->isOnline($input);
        $messageCode=MessageCode::DATA_OK;
        $message=MessageCode::getMessage(MessageCode::DATA_OK);
        $data=[
            'is_online'=>$isOnline
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }


}
