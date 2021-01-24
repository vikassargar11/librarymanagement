<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'localhost',                    
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'art18-12',
                ]
            ],            
        ],        
    ],
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
        'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).        
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
];
