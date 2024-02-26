<?php

namespace App\Service;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Context\ApplicationContext;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class BaseService{

    /**
     * /**
     *  发号器
     * @return int
     */
    public function generateId(): int
    {
        try{
            $container = ApplicationContext::getContainer();
            $generator = $container->get(IdGeneratorInterface::class);
            $id=$generator->generate();
        }catch (\Exception $e){
            $id=0;
        }
        return $id;
    }
}