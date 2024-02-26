<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class Box extends Model
{
    protected ?string $table = 'delivery_box';
    const TABLE_NAME = 'delivery_box';
    const CREATED_AT = null;

    const UPDATED_AT = null;

}