<?php

namespace App\Service\Jwt;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use function Hyperf\Config\config;

class JwtService
{
   /**
     * 获取SSO jwt
     * @return array
     */
    public function encodeJwt($tokenData): array
    {
        $jwtTTL = config('jwt.ttl');
        $secretKey =  config('jwt.secret_key');
        $iss =  config('jwt.iss');
        $time=time();
        $expTime = $time + intval($jwtTTL); //换签有效期
        $payload = [
            'iss' => $iss,
            'aud' => $iss,
            'iat' => $time,
            'nbf' => $time,
            'exp' => $expTime,
            'data'=>$tokenData
        ];
        $jwt=JWT::encode($payload, $secretKey, 'HS256');
        return [
            'exp' => $expTime,
            'jwt' => $jwt,
        ];
    }

    /**
     * @param $jwt
     * @return mixed
     */
    public function decodeJwt($jwt): mixed
    {
        try{
            $secretKey =  config('jwt.secret_key');
            $data=JWT::decode($jwt, new Key($secretKey, 'HS256'));
        }catch (\Throwable $e){
            $data=null;
        }
        return $data;
    }
}