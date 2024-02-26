<?php
declare(strict_types=1);
/**
 * 取值监听控制器
 * @author xiaonian
 * @date 2024-02-21
 */
namespace App\Listener;

use Hyperf\Database\Events\StatementPrepared;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use PDO;

#[Listener]
class FetchModeListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            StatementPrepared::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof StatementPrepared) {
            $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        }
    }
}