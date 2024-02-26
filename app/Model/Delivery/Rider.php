<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class Rider extends Model
{
    protected ?string $table = 'delivery_rider';
    const TABLE_NAME = 'delivery_rider';
    const CREATED_AT = null;

    const UPDATED_AT = null;
}