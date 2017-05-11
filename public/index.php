<?php 

/**
 * Help to built the api in server
 * For Development
 */
if (PHP_SAPI != 'apache2handler' || PHP_OS != 'WINNT') {
    die('THIS API SHOULD NOT RUN...');
}

/**
 * Include main file for the application run
 */
require_once __DIR__ . '/../bootstrap/app.php';

// initialize application
$app->run();
