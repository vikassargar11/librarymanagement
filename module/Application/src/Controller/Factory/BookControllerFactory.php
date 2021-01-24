<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\BookManager;
use Application\Controller\BookController;

/**
 * factory initialise the services manager,
 */
class BookControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager 	= $container->get('doctrine.entitymanager.orm_default');
        $bookManager = $container->get(BookManager::class);
		
        return new BookController($entityManager, $bookManager);
    }
}