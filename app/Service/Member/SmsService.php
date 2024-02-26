<?php
declare(strict_types=1);
/**
 * Index 短信服务
 * @author xiaonian
 * @date 2024-02-21
 */

namespace App\Service\Member;
use App\Model\Delivery\SmsLog;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Redis\Redis;
use GuzzleHttp\Client;
use Hyperf\Guzzle\CoroutineHandler;
use GuzzleHttp\HandlerStack;

class SmsService
{
    private string $sendApi='/Sms/Api/Send';

    /**
     * 获取短信验证码
     * @return int
     */
    public function getSmsCode($mobile,$codeType): int
    {
        //0=获取成功，1=60秒内不能重复获取验证码 2=短信发送接口请求失败 3=短信获取失败

        //判断是否已经获取验证码
        $frequentKey = $codeType.':frequent:'.$mobile;
        $valueKey = $codeType.':value:'.$mobile;
        $container = ApplicationContext::getContainer();
        try {
            $redis = $container->get(Redis::class);
            $redis->select(3);
            $isExist=$redis->exists($frequentKey);
            if($isExist){
                //60秒内不能重复获取验证码
                $code=1;
            }else{
                $smsCode=$this->generateCode();

                //短信验证码60秒内不能重复获取验证码
                $redis->setex($frequentKey,60,1);

                //短信验证码300秒内有效
                $isSet=$redis->set($valueKey,$smsCode,300);
                if($isSet){
                    $content = $this->getContent($smsCode);
                    //$result=$this->sendSms($smsCode,$content);
                    $result=['code'=>0];
                    if(isset($result['code']) && $result['code']==0){
                        $code=0;
                    }else{
                        $code=2;
                        $redis->del($frequentKey);
                        $redis->del($valueKey);
                        $smsLog=[
                            'mobile'=>$mobile,
                            'status'=>2,
                            'sms_id'=>$result['data']??0,
                            'type'=>1,
                            'msg'=>$result['msg']??'短信发送接口请求失败',
                            'create_time'=>time(),
                        ];
                        SmsLog::query()->insert($smsLog);
                    }
                }else{
                    $code=3;
                }
            }
            $redis->close();
        } catch (\Throwable $e) {
            $code=3;
            $smsLog=[
                'mobile'=>$mobile,
                'status'=>2,
                'sms_id'=>0,
                'type'=>1,
                'msg'=>'Redis异常',
                'create_time'=>time(),
            ];
            SmsLog::query()->insert($smsLog);
        }
        return $code;
    }

    /**
     * @param $mobile
     * @param $content
     * @return array
     */
    private function sendSms($mobile,$content): array
    {
        /**
         * @var ConfigInterface $config
         */
        $host = $config->get('sms.host');
        $secretName = $config->get('sms.host');
        $secretKey = $config->get('sms.host');
        $params = [
            'SecretName' =>$secretName,
            'SecretKey'  =>$secretKey,
            'Mobile'     =>$mobile,
            "Content"    =>$content,
        ];
        $options=[
            'headers' => [
                'Content-Type'=> 'application/json; charset=UTF-8',
                'Accept' => 'application/json'
            ],
            'json' => json_encode($params)
        ];
        try {
            $client = new Client([
                'base_uri' => $host,
                'handler' => HandlerStack::create(new CoroutineHandler()),
                'timeout' => 5,
                'swoole' => [
                    'timeout' => 10,
                    'socket_buffer_size' => 1024 * 1024 * 2,
                ],
            ]);
            $response = $client->post('/Sms/Api/Send', $options);
            $result=json_decode($response->getBody()->getContents());
        }catch (\Throwable $e){
            $result=[
                "code"=>1,
                "msg"=>'接口请求异常',
                "data"=>0
            ];
        }
        return $result;
    }

    /**
     * 随机生成验证码
     * @return int
     */
    private function generateCode(): int
    {
        return mt_rand(100000, 999999);
    }

    /**
     * 获取内容
     * @param $code
     * @return string
     */
    private function getContent($code)
    {
        $content = '验证码：' . $code . '，用于手机号验证码登陆，5分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。';
        return $content;
    }

    /**
     * 验证验证码
     * @return bool
     */
    public function verify($mobile, $smsCode, $codeType): bool
    {
        $valueKey = $codeType.':value:'.$mobile;
        try {
            $container = ApplicationContext::getContainer();
            $redis = $container->get(Redis::class);
            $redis->select(3);
            $codeOnServer = $redis->get($valueKey);
            if($codeOnServer==$smsCode && $smsCode){
                $result=true;
                $redis->del($valueKey);
            }else{
                $result=false;
            }
            $redis->close();
        }catch (\Throwable $e){
            $result=false;
        }
        return $result;
    }
}