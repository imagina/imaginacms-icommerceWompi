<?php

return [
    'name' => 'Icommercewompi',
    'paymentName' => 'icommercewompi',

    /*
    * Methods
    */
    'methods' => [
    	// Webcheckout
    	[
    		'title' => 'icommercewompi::icommercewompis.methods.wompi.title',
    		'description' => 'icommercewompi::icommercewompis.methods.wompi.description',
            'name' => 'icommercewompi',
    		'status' => 1,
    	],
    	// Payment Sources | Fuentes de Pago 
    	[
    		'title' => 'icommercewompi::icommercewompis.methods.wompiPaymentSources.title',
    		'description' => 'icommercewompi::icommercewompis.methods.wompiPaymentSources.description',
            'name' => 'icommercewompipaymentsources',
    		'status' => 0,
            'parent_name' => 'icommercewompi'
    	],
    ],

    /*
    * API URL
    */
    'apiUrl' => [
        'acceptanceToken' => [
            'sandbox' => 'https://sandbox.wompi.co/v1/merchants/',
            'production' => 'https://production.wompi.co/v1/merchants/',
        ],
        'paymentSources' => [
            'sandbox' => 'https://sandbox.wompi.co/v1/payment_sources',
            'production' => 'https://production.wompi.co/v1/payment_sources',
        ],
        'transactions' => [
            'sandbox' => 'https://sandbox.wompi.co/v1/transactions',
            'production' => 'https://production.wompi.co/v1/transactions',
        ],
    ],
    
];
