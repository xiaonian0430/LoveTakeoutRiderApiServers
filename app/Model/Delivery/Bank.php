<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class Bank extends Model
{
    protected ?string $table = 'delivery_bank';
    const TABLE_NAME = 'delivery_bank';
    const CREATED_AT = null;

    const UPDATED_AT = null;

}