<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index1',
                    ],
                ],
            ],
            'book_issue' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/book/issue',
                    'defaults' => [
                        'controller' => Controller\BookController::class,
                        'action'     => 'bookIssue',
                    ],
                ],
            ],
            'book_return' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/book/return',
                    'defaults' => [
                        'controller' => Controller\BookController::class,
                        'action'     => 'bookReturn',
                    ],
                ],
            ],
            'total_rent_paid' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/book/total-rent',
                    'defaults' => [
                        'controller' => Controller\BookController::class,
                        'action'     => 'totalRent',
                    ],
                ],
            ],
            'book_report' => [
                'type'    => 'segment',
                'options' => [
                    'route' => '/book/detail/[:action][/:reportdate]',
                    'constraints' => [
                       'reportdate' => '[a-zA-Z0-9_-]+',
                       'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\BookController::class,
                        'action'     => 'report',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\BookController::class => Controller\Factory\BookControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [            
            Service\BookManager::class => Service\Factory\BookManagerFactory::class,          
        ],
    ],
    'doctrine' => [
        'driver' => [
            'Application_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                     'Application\Entity' => 'Application_driver'
                ]
            ]
        ]
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        
    ],
];
