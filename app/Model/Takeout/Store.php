<?php

declare(strict_types=1);

namespace App\Model\Takeout;

use Hyperf\DbConnection\Model\Model;

class Store extends Model
{
    protected ?string $table = 'lt_store';
    const TABLE_NAME = 'lt_store';

    const CREATED_AT = null;

    const UPDATED_AT = null;
}