<?php 

/**
 * Well this file is the main config file for
 * sql server conenction, this credentials
 * can be diferent in diferent environments
 */
return [
    'sqlsrv' => [
        'server'   => getenv('SQL_SERVER'),
        'port'     => getenv('SQL_PORT'),
        'database' => getenv('SQL_DATABASE'),
        'username' => getenv('SQL_USERNAME'),
        'password' => getenv('SQL_PASSWORD'),
    ],
];
