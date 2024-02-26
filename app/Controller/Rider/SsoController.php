<?php

declare(strict_types=1);
/**
 * sso 登录控制器
 * @author xiaonian
 * @date 2024-02-21
 */

namespace App\Controller\Rider;
use App\Constants\MessageCode;
use App\Constants\RedisKey;
use App\Controller\BaseController;
use App\Service\Member\SmsService;
use App\Service\Member\SsoService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class SsoController extends BaseController
{
    /**
     * 密码登录
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function passwordLogin(ResponseInterface $response) : Psr7ResponseInterface
    {
        $account = $this->request->input('account', '');
        $password = $this->request->input('password', '');

        $result=(new SsoService())->passwordLogin($account,$password);
        if($result['code']==0){
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage(MessageCode::DATA_OK);
            $token=$result['token'];
        }else if($result['code']==1){
            $messageCode=1001;
            $message=MessageCode::getMessage(MessageCode::LOGIN_ACCOUNT_NO_EXIST);
            $token='';
        }else if($result['code']==2){
            $messageCode=1002;
            $message=MessageCode::getMessage(MessageCode::LOGIN_PASSWORD_ERROR);
            $token='';
        }else{
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::LOGIN_FAIL);
            $token='';
        }
        $data=[
            "message"=>$message,
            "code"=>$messageCode,
            "data"=>[
                "token"=>$token
            ]
        ];
        return $response->json($data);
    }

    /**
     * 短信验证登录
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function smsCodeLogin(ResponseInterface $response) : Psr7ResponseInterface
    {
        $mobile = $this->request->input('mobile', '');
        $code = $this->request->input('code', '');
        $result=(new SsoService())->smsLogin($mobile,$code);
        if($result['code']==0){
            $status=$result['status'];
            $token=$result['token'];
            if($status==0){
                $messageCode=MessageCode::DATA_OK;
                $message=MessageCode::getMessage(MessageCode::DATA_OK);
            }else if($status==1){
                $messageCode=1002;
                $message=MessageCode::getMessage(MessageCode::NO_REGISTER);
            }else if($status==2){
                $messageCode=1002;
                $message=MessageCode::getMessage(MessageCode::OVER_STATE_ING);
            }else{
                $messageCode=1003;
                $message=MessageCode::getMessage(MessageCode::OVER_STATE_FAIL);
            }
        }else if($result['code']==1){
            $messageCode=1001;
            $message=MessageCode::getMessage(MessageCode::LOGIN_SMS_CODE_ERROR);
            $token='';
        }else{
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::LOGIN_SMS_CODE_VERITY_FAIL);
            $token='';
        }
        $data=[
            "message"=>$message,
            "code"=>$messageCode,
            "data"=>[
                "token"=>$token
            ]
        ];
        return $response->json($data);
    }

    /**
     * 获取短信验证码
     * @param ResponseInterface $response
     * @return Psr7ResponseInterface
     */
    public function getSmsCode(ResponseInterface $response): Psr7ResponseInterface
    {
        $mobile = $this->request->input('mobile', '');

        $codeType=RedisKey::RIDER_LOGIN_CODE;
        $code=(new SmsService())->getSmsCode($mobile,$codeType);
        if($code==0){
            $messageCode=MessageCode::DATA_OK;
            $message=MessageCode::getMessage($messageCode);
            $send=1;
        }else if($code==1){
            $messageCode=MessageCode::SMS_FREQ_ERROR;
            $message=MessageCode::getMessage(MessageCode::SMS_FREQ_ERROR);
            $send=0;
        }else{
            $messageCode=MessageCode::DATA_ERROR;
            $message=MessageCode::getMessage(MessageCode::GET_SMS_CODE_FAIL);
            $send=0;
        }
        $data=[
            "send"=>$send,
        ];
        $json=$this->jsonData($messageCode,$data,$message);
        return $response->json($json);
    }
}
