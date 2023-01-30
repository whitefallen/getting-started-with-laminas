<?php

namespace Blog\Factory;

use Blog\Model\LaminasDbSqlCommand;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
class LaminasDbSqlCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LaminasDbSqlCommand
    {
        return new LaminasDbSqlCommand($container->get(AdapterInterface::class));
    }
}