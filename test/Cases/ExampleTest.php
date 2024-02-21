<?php

declare(strict_types=1);
/**
 * 测试用例
 * @author xiaonian
 * @date 2024-02-18
 */

namespace HyperfTest\Cases;

use Hyperf\Testing\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    public function testExample()
    {
        $this->get('/')->assertOk()->assertSee('Hyperf');
    }
}
