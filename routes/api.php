<?php 

/**
 * API Routes 
 */

$app->group('/api', function () {
    $this->post('/query', 'ApiController:index');
});

$app->group('/api/auth', function () {

    // signin 
    $this->post('/signin', 'ApiController:signin');
});

