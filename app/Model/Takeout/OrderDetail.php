<?php

declare(strict_types=1);

namespace App\Model\Takeout;

use Hyperf\DbConnection\Model\Model;

class OrderDetail extends Model
{
    protected ?string $table = 'lt_order_details';

    const CREATED_AT = null;

    const UPDATED_AT = null;
}