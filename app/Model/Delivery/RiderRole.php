<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class RiderRole extends Model
{
    protected ?string $table = 'delivery_rider_role';
    const TABLE_NAME = 'delivery_rider_role';
    const CREATED_AT = null;

    const UPDATED_AT = null;
}