<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

class Order extends Model
{
    protected ?string $table = 'order';

    const CREATED_AT = null;

    const UPDATED_AT = null;
}