<?php

declare(strict_types=1);

namespace App\Model\Takeout;

use Hyperf\DbConnection\Model\Model;

class Order extends Model
{
    protected ?string $table = 'lt_order';

    const CREATED_AT = null;

    const UPDATED_AT = null;
}