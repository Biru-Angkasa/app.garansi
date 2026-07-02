<?php

return [
    'lokasi' => [
        'jakarta' => [
            'nama'    => 'Jakarta',
            'nomor'   => '6281234567890',
            'url'     => env('WAHA_URL_JAKARTA', 'http://localhost:3001'),
            'session' => 'default', // <-- UBAH JADI DEFAULT
            'api_key' => env('WAHA_API_KEY_JAKARTA', ''),
        ],
        'makassar' => [
            'nama'    => 'Makassar',
            'nomor'   => '6287897137231',
            'url'     => env('WAHA_URL_MAKASSAR', 'http://localhost:3002'),
            'session' => 'default', // <-- UBAH JADI DEFAULT
            'api_key' => env('WAHA_API_KEY_MAKASSAR', ''),
        ],
        'surabaya' => [
            'nama'    => 'Surabaya',
            'nomor'   => '6281234567892',
            'url'     => env('WAHA_URL_SURABAYA', 'http://localhost:3003'),
            'session' => 'default', // <-- UBAH JADI DEFAULT
            'api_key' => env('WAHA_API_KEY_SURABAYA', ''),
        ],
    ],
];