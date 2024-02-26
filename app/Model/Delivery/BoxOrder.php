<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class BoxOrder extends Model
{
    protected ?string $table = 'delivery_box_order';
    const TABLE_NAME = 'delivery_box_order';
    const CREATED_AT = null;

    const UPDATED_AT = null;

}