<?php


return [
    'gateway' => env('IPG', 'IDPay'),

    'PEP' => [
        'auth' => env('PEP_AUTH', ''),
        'callBackMethod' => env('PEP_CALLBACK_METHOD', 'get'),
        'callbackSuccessStatus' => env('PEP_CALLBACK_SUCCESS_STATUS', 200),
        'verifySuccessStatus'   => env('PEP_Verify_SUCCESS_STATUS', 'approved'),
        'host' => env('PEP_HOST', ''),
    ],

    'IDPay' => [
        'apiKey' => env('IDPAY_APIKEY__', ''),
        'callBackMethod' => env('IDPAY_CALLBACK_METHOD', 'post'),
        'callbackSuccessStatus' => env('IDPay_CALLBACK_SUCCESS_STATUS', 10),
        'verifySuccessStatus'   => env('IDPay_Verify_SUCCESS_STATUS', 'approved'),
        'host' => env('IDPAY_HOST', ''),

    ]
];
