<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class RiderTimeLine extends Model
{
    protected ?string $table = 'delivery_rider_timeline';
    const TABLE_NAME = 'delivery_rider_timeline';
    const CREATED_AT = null;

    const UPDATED_AT = null;
}