<?php

use Illuminate\Routing\Router;

Route::prefix('icommercewompi')->group(function (Router $router) {
    $router->get('/', [
        'as' => 'icommercewompi.api.wompi.init',
        'uses' => 'IcommerceWompiApiController@init',
    ]);

    $router->post('/confirmation', [
        'as' => 'icommercewompi.api.wompi.confirmation',
        'uses' => 'IcommerceWompiApiController@confirmation',
    ]);

    //======================================================== Payment Sources | Form Route Post

    $router->post('/paymentsources/process-token/{eUrl}', [
        'as' => 'icommercewompi.api.wompipaymentsources.processToken',
        'uses' => 'IcommerceWompiApiController@processToken',
    ]);

    //======================================================== ELIMINAR ESTO LUEGO
    
    $router->post('/simulate-recurrence', [
        'as' => 'icommercewompi.api.wompi.simulateRecurrence',
        'uses' => 'IcommerceWompiApiController@simulateRecurrence',
    ]);
    
});
