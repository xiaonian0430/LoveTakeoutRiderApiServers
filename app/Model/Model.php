<?php

declare(strict_types=1);
/**
 * 模型
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Model;

use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;
}
