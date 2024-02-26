<?php

declare(strict_types=1);
/**
 * Index 控制器
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Controller;

use App\Constants\MessageCode;

class BaseController extends AbstractController
{
    public function jsonData($code,$data,$message='')
    {
        if(empty($message)){
            $message=MessageCode::getMessage($code);
        }
        $data=[
            "message"=>$message,
            "code"=>$code,
            "data"=>$data
        ];
        return $data;
    }
}
