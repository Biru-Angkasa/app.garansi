<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Odoo ERP Configuration
    |--------------------------------------------------------------------------
    |
    | Credential dan daftar instance Odoo ERP untuk fitur scraping invoice.
    | Semua instance menggunakan username & password yang sama.
    |
    */

    'username' => env('ODOO_USERNAME', ''),
    'password' => env('ODOO_PASSWORD', ''),

    'instances' => [
        'jakarta' => [
            'nama' => 'Odoo Jakarta',
            'url'  => env('ODOO_JKT_URL', 'https://ojkt.erpsyst.com'),
            'db'   => env('ODOO_JKT_DB', 'ojkt'),
        ],
        'surabaya' => [
            'nama' => 'Odoo Surabaya',
            'url'  => env('ODOO_SBY_URL', 'https://osby.erpsyst.com'),
            'db'   => env('ODOO_SBY_DB', 'osby'),
        ],
        'makassar' => [
            'nama' => 'Odoo Makassar',
            'url'  => env('ODOO_MKS_URL', 'https://omks.erpsyst.com'),
            'db'   => env('ODOO_MKS_DB', 'omks'),
        ],
    ],
];
