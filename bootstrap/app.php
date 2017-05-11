<?php 

/**
 * ===========================================
 * SET TIMEZONE 
 *
 */
date_default_timezone_set("Europe/Lisbon");

/**
 * ===========================================
 * REQUIRE VENDOR
 *
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use Sage\Controllers\ApiController;
use Noodlehaus\Config;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\{StreamHandler, FirePHPHandler};
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\{CliDumper, HtmlDumper};
use Sage\Middlewares\AppServiceMiddleware;
use Sage\MsSqlConnector\SqlConnector;
use RKA\Middleware\IpAddress;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\{Builder, Parser, ValidationData};
// use PHPMailer;

/**
 * ===========================================
 * CHECK IF EXIST ANY ".env" FILE TO LOAD
 *
 */
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = (new Dotenv(__DIR__ . '/../'))->load();
}

/**
 * ===========================================
 * START SLIM 3 FRAMEWORK
 *
 */
$app = new App([
    'settings' => [
        'displayErrorDetails' => environment(),
    ],
]);


// extract container 
$container = $app->getContainer();

/**
 * ===========================================
 * CONFIGS
 *
 */
$container['configs'] = function ($c) {
    return new Config(__DIR__ . '/../configs');
};

/**
 * ===========================================
 * CONFIGURE DUMP HANDER FROM SYMPHONY
 *
 */
VarDumper::setHandler(function ($var) use ($container) {

    $cloner = new VarCloner;
    $cliDumper = new CliDumper;

    $htmlDumper = new HtmlDumper;
    $htmlDumper->setStyles($container['configs']->get('dumper'));

    $dumper = PHP_SAPI === 'cli' ? $cliDumper : $htmlDumper;
    $dumper->dump($cloner->cloneVar($var));
});

/**
 * ===========================================
 * MONOLOG
 *
 */
$container['logger'] = function($c) {

    $logger = new Logger($c->configs->get('logger.name'));

    $logger->pushHandler(
        new StreamHandler(
            $c->configs->get('logger.path'), 
            $c->configs->get('logger.level')
        )
    );

    $logger->pushHandler(new FirePHPHandler);

    return $logger;
};

/**
 * ===========================================
 * SQL SERVER CUSTOM CONNECTOR
 *
 */
$container['sqlserver'] = function ($c) {
    return (new SqlConnector($c->configs->get('sqlsrv')))->connect();
};

/**
 * ===========================================
 * JWT BUILDER
 *
 */
$container['jwt'] = function ($c) {
    return new Builder;
};

/**
 * ===========================================
 * JWT SIGNER
 *
 */
$container['signer'] = function ($c) {
    return new Sha256;
};

/**
 * ===========================================
 * JWT PARSER
 *
 */
$container['parser'] = function ($c) {
    return new Parser;
};

/**
 * ===========================================
 * JWT VALIDATOR
 *
 */
$container['validator'] = function ($c) {
    return new ValidationData;
};

/**
 * ===========================================
 * CONTROLLERS 
 * LOAD MULTIPLE CONTROLLERS AT ONE TIME ACCORDING
 * TO CONTOLLERS FILE IN CONFIGS DIR
 *
 */
$controllers = $container->configs->get('controllers');

foreach ($controllers as $name => $controller) {
    $container[$name] = function ($c) use ($controller) {
        return new $controller($c);
    };
}

/**
 * ===================================================
 * Override the default Not Found Handler
 * (404) Page not found
 */
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
                    ->withStatus(404)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(json_encode([
                        'code' => 404,
                        'message' => 'The URI was not found.',
                    ]));
    };
};

/**
 * ===================================================
 * Override the default Not Allowed Handler
 * (405) Method not allowed
 */
$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode([
                'code' => 405,
                'message' => 'Method not allowed.',
            ]));
    };
};

/**
 * ===================================================
 * Override the default Server Error Handler
 * (500) Internal server error
 */
$c['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode([
                'code' => 500,
                'message' => 'Internal server error.',
                'errors' => $error,
            ]));
    };
};

/**
 * ===================================================
 * ROOT MIDDLEWARES
 *
 */
$app->add(new AppServiceMiddleware($container));
$app->add(new IpAddress(true, []));

/**
 * ===================================================
 * ROUTES
 *
 */
require_once __DIR__ . '/../routes/api.php';
