<?php

namespace Blog\Factory;

use Blog\Controller\ListController;
use Blog\Model\PostRepositoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ListControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ListController
    {
        return new ListController($container->get(PostRepositoryInterface::class));
    }
}