<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class BalanceLog extends Model
{
    protected ?string $table = 'delivery_balance_log';
    const TABLE_NAME = 'delivery_balance_log';

    const CREATED_AT = null;

    const UPDATED_AT = null;
}