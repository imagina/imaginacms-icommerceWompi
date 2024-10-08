<?php

use Illuminate\Routing\Router;

Route::prefix('icommercewompi')->group(function (Router $router) {
    $locale = LaravelLocalization::setLocale() ?: App::getLocale();

    $router->get('/{eUrl}', [
        'as' => 'icommercewompi',
        'uses' => 'PublicController@index',
    ]);

    $router->get('/payment/response/{orderId}', [
        'as' => 'icommercewompi.response',
        'uses' => 'PublicController@response',
    ]);

     //========================================== Payment Sources | Payment

    $router->get('/payment/{eUrl}/{ps}', [
        'as' => 'icommercewompi.payment',
        'uses' => 'PublicController@payment',
        'middleware' => ['logged.in']
    ]);
    
});
