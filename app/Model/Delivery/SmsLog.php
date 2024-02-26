<?php

declare(strict_types=1);
/**
 * 短信日志模型
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class SmsLog extends Model
{
    protected ?string $table = 'delivery_sms_log';
    const TABLE_NAME = 'delivery_sms_log';
    const CREATED_AT = null;

    const UPDATED_AT = null;

}