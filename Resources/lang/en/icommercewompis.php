<?php

return [
    'single' => 'Wompi',
    'description' => 'The description of the method',
    'list resource' => 'List icommercewompis',
    'create resource' => 'Create icommercewompis',
    'edit resource' => 'Edit icommercewompis',
    'destroy resource' => 'Destroy icommercewompis',
    'title' => [
        'icommercewompis' => 'IcommerceWompi',
        'create icommercewompi' => 'Create a icommercewompi',
        'edit icommercewompi' => 'Edit a icommercewompi',
    ],
    'button' => [
        'create icommercewompi' => 'Create a icommercewompi',
    ],
    'table' => [
        'description' => 'Description',
        'activate' => 'Activate',
        'publicKey' => 'Public Key',
        'privateKey' => 'Private Key',
        'eventSecretKey' => 'Event Secret Key',
        'mode' => 'Mode',
        'signatureIntegrityKey' => 'Signature Integrity Key',
        'paymentAttemps' => 'Payment Attemps (Only to: WOMPI RECURRENCIA)'
    ],
    'form' => [
    ],
    'messages' => [
        'minimum' => 'Total order minimum not allowed',
        'not payment method added' => "You do not have an added payment method. Click on the 'Save your payment method' button'",
        'click to add method' => 'Click on the button to start the process',
        'make payment' => 'Pay',
        'select a payment method' => 'Select a payment method',
        'executing payment' => 'Executing payment, please wait a moment',
        'loading' => 'Loading...'
    ],
    'validation' => [
    ],
    'methods' => [
        'wompi' => [
            'title' => 'Wompi',
            'description' => 'Pago Inmediato'
        ],
        'wompiPaymentSources' => [
            'title' => 'Wompi | Recurrencia',
            'description' => 'Pago Periódico Automático'
        ],
    ]
];
