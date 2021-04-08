<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'icommercewompi'], function (Router $router) {
    
    $router->get('/', [
        'as' => 'icommercewompi.api.wompi.init',
        'uses' => 'IcommerceWompiApiController@init',
    ]);

    $router->get('/confirmation', [
        'as' => 'icommercewompi.api.wompi.confirmation',
        'uses' => 'IcommerceWompiApiController@confirmation',
    ]);

    $router->post('/confirmation', [
        'as' => 'icommercewompi.api.wompi.confirmation',
        'uses' => 'IcommerceWompiApiController@confirmation',
    ]);

});