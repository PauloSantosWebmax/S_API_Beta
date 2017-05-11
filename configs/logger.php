<?php 

/**
 * ===========================================
 * LOGGER SETTINGS
 *
 */

return [
    'logger' => [
        'name' => getenv('LOGGER_NAME') || 'SAGE LOG',
        'level' => Monolog\Logger::DEBUG,
        'path' => __DIR__ . '/../logs/app.log',
    ],
];
