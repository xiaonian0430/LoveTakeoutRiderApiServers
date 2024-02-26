<?php

declare(strict_types=1);

namespace App\Model\Delivery;

use Hyperf\DbConnection\Model\Model;

class Site extends Model
{
    protected ?string $table = 'delivery_site';
    const TABLE_NAME = 'delivery_site';

    const CREATED_AT = null;

    const UPDATED_AT = null;
}