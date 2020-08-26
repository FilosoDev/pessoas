<?php

return [
    'title' => 'Pessoas',
    'dashboard_url' => config('app.url'),
    'logout_method' => 'GET',
    'logout_url' => config('app.url') . '/logout',
    'login_url' => config('app.url') . '/login',
    'menu' => [
        [
            'text' => 'Busca',
            'url'  =>  config('app.url') . '/search',
            'can'  => 'admin'
        ],
        [
            'text' => 'Pessoas Autorizadas',
            'url'  => config('app.url') . '/users',
            'can'  => 'admin'
        ],
    ]
];
