<?php


return [
    'gateway' => env('IPG', 'PEP'),

    'PEP' => [
        'auth' => env('PEP_AUTH', ''),
        'callBackMethod' => env('PEP_CALLBACK_METHOD', 'get'),
        'callbackSuccessStatus' => env('PEP_CALLBACK_SUCCESS_STATUS', 200),
        'verifySuccessStatus'   => env('PEP_Verify_SUCCESS_STATUS', 'approved'),
        'host' => env('PEP_HOST', 'http://msh.ariaco.org:3003'),
    ],

    'IDPay' => [
        'apiKey' => env('IDPAY_APIKEY__', ''),
        'callBackMethod' => env('IDPAY_CALLBACK_METHOD', 'post'),
        'callbackSuccessStatus' => env('IDPay_CALLBACK_SUCCESS_STATUS', 10),

    ]
];
