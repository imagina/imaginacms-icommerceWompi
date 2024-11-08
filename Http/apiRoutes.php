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


    /**
     * Routes to Crud
     */
    Route::prefix('/v1')->group(function (Router $router) {

        $router->apiCrud([
            'module' => 'icommercewompi',
            'controller' => 'PaymentSourcesApiController',
            'prefix' => 'payment-sources',
            'permission' => 'icommercewompi.paymentsources',
            //'middleware' => ['create' => [], 'index' => [], 'show' => [], 'update' => [], 'delete' => [], 'restore' => []],
            // 'customRoutes' => [ // Include custom routes if needed
            //  [
            //    'method' => 'post', // get,post,put....
            //    'path' => '/some-path', // Route Path
            //    'uses' => 'ControllerMethodName', //Name of the controller method to use
            //    'middleware' => [] // if not set up middleware, auth:api will be the default
            //  ]
            // ]
        ]);
    
    });
    
});
