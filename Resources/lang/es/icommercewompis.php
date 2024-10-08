<?php

return [
    'single' => 'Wompi',
    'description' => 'Descripcion del Modulo',
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
        'pay' => 'Pagar',
    ],
    'table' => [
        'description' => 'Description',
        'activate' => 'Activate',
        'publicKey' => 'Public Key',
        'privateKey' => 'Private Key',
        'eventSecretKey' => 'Event Secret Key',
        'mode' => 'Mode',
        'signatureIntegrityKey' => 'Signature Integrity Key',
        'type' => 'Tipo',
        'last 4 digits' => 'Ultimos 4 digitos / Nro Nequi',
        'title' => 'Titulo',
        'first name' => 'Nombre',
        'last name' => 'Apellido',
        'shipping amount' => 'Monto de Envío',
        'paymentAttemps' => 'Intentos de Pago (Disponble solo para el metodo: WOMPI RECURRENCIA)'
    ],
    'form' => [
    ],
    'title' => [
        'welcome' => 'Wompi - Bienvenido',
        'payment methods' => 'Medios de Pago',
        'select payment method' => 'Selecciona el medio de pago',
        'add payment method' => 'Agregar medio de pago',
        'payment resumen' => 'Resumen de Pago'
    ],
    'messages' => [
        'minimum' => 'El TOTAL de la orden no cumple con el monto minimo',
        'not payment method added' => "No posees un medio de pago agregado. Haz click en el boton 'Guarda tu método de pago'",
        'click to add method' => 'Haz click en el botón para iniciar el proceso',
        'make payment' => 'Realizar pago',
        'select a payment method' => 'Debes seleccionar un medio de pago'
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
