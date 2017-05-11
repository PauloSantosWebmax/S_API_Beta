<?php 

function dd($value)
{
    die(dump($value));
}

function env(string $value)
{
    return $_ENV[$value] ?: null;
}

function environment()
{
    $environment = env('APP_ENVIRONMENT') ?: 'production';
    return $environment == 'development' ? true : false;
}
