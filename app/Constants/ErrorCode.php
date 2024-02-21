<?php

declare(strict_types=1);
/**
 * 错误码配置
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;
}
