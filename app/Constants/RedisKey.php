<?php

declare(strict_types=1);
/**
 * Redis键配置
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class RedisKey extends AbstractConstants
{
    public const RIDER_IS_ONLINE = 'delivery_rider_is_online';
    public const RIDER_ROLE = 'delivery_rider_role';
    public const RIDER_SITE_ID = 'delivery_rider_site_id';

    //登录验证码
    public const RIDER_LOGIN_CODE = 'delivery_rider_login_code';
    public const RIDER_SSO = 'delivery_rider_sso';
}
