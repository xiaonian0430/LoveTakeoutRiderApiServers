<?php

namespace App\Service\Member;
use App\Constants\RedisKey;
use App\Model\Delivery\Rider;
use App\Service\Jwt\JwtService;
use Hyperf\Context\ApplicationContext;
use Hyperf\Redis\Redis;

class SsoService
{
    /**
     * 密码登录
     * @param $mobile
     * @param $password
     * @return array
     */
    public function passwordLogin($mobile,$password) :array
    {
        //0成功，1账号不存在，2密码错误 ,3=登录失败
        $token='';
        $password=$this->verPassword($password);
        $cols=[
            'rider_id',
            'password',
        ];
        try{
            $result = Rider::query()
                ->where('mobile','=',$mobile)
                ->first($cols);
            if(empty($result)){
                $code=1;
            }else if(isset($result->password) && $result->password==$password){
                $token=$this->setSsoToken($result['rider_id']);
                $code=0;
            }else{
                $code=2;
            }
        }catch (\Throwable $e){
            echo $e.PHP_EOL;
            $code=3;
        }
        return [
            'code'=>$code,
            'token'=>$token
        ];
    }

    /**
     * @param String $password
     * @验证密码
     */
    private function verPassword(String $password): string
    {
        $password =  md5($password);

        return $password;
    }

    /**
     * sms登录
     * @param $mobile
     * @param $code
     * @return array
     */
    public function smsLogin($mobile,$code) :array
    {
        //0=正常 1=未注册 2=资料审核中 3=未通过审核
        $status=0;

        //0成功，1验证码错误
        $token='';
        $codeType=RedisKey::RIDER_LOGIN_CODE;
        $isVerify=(new SmsService())->verify($mobile,$code,$codeType);
        if($isVerify){
            $code=0;
            $cols=[
                'rider_id',
                'over_state'
            ];
            try{
                $result = Rider::query()->where('mobile', $mobile)->first($cols);
                if(empty($result)){
                    $status=1;
                }else if($result->over_state==1){
                    $token=$this->setSsoToken($result->rider_id);
                }else if($result->over_state==0){
                    $status=2;
                }else{
                    $status=3;
                }
            }catch (\Throwable $e){
                $code=2;
            }
        }else{
            $code=1;
        }
        return [
            'code'=>$code,
            'status'=>$status,
            'token'=>$token
        ];
    }

    /**
     * 设置登录数据
     * @param $riderId
     * @return string
     */
    private function setSsoToken($riderId) :string
    {
        $tokenData=[
            'rider_id'=>$riderId,
        ];
        $jwtResult=(new JwtService())->encodeJwt($tokenData);
        $token=$jwtResult['jwt'];
        $exp=$jwtResult['exp'];
        $ssoKey=RedisKey::RIDER_SSO.':app:'.$riderId;
        $container = ApplicationContext::getContainer();
        $redis = $container->get(Redis::class);
        $redis->select(3);
        $redis->setex($ssoKey,$exp,$token);
        $redis->close();
        return $token;
    }

    /**
     * 获取登录数据
     * @param $riderId
     * @return string
     */
    public function getSsoToken($riderId):string
    {
        try{
            $ssoKey=RedisKey::RIDER_SSO.':app:'.$riderId;
            $container = ApplicationContext::getContainer();
            $redis = $container->get(Redis::class);
            $redis->select(3);
            $token=$redis->get($ssoKey);
            $redis->close();
        }catch (\Throwable $e){
            $token='';
        }
        return $token;
    }
}