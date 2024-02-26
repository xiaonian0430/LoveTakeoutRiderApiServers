<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class OrderOperationLog extends Model
{
    protected ?string $table = 'delivery_order_operation_log';
    const TABLE_NAME = 'delivery_order_operation_log';
    const CREATED_AT = null;

    const UPDATED_AT = null;

}