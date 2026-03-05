<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'channels' => [ 
        'kbzpay_qr' => [
            'base_url'      => env("KBZ_PAY_BASE_URL", "..."),
            'merchant_code' => env("KBZ_PAY_MERCHANT_CODE"),
            'app_id'        => env("KBZ_PAY_APP_ID"),
            'app_key'       => env("KBZ_PAY_APP_KEY"),
        ],
    ]
];