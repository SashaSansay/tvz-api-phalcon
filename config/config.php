<?php

use Phalcon\Config;

return new Config(
    [
        'database'    => [
            'adapter'  => 'Mysql',
            'host'     => 'localhost',
            'username' => '',
            'password' => '',
            'name'     => '',
        ],
        'application' => [
            'modelsDir' => __DIR__ . '/../models/',
            'baseUri'   => 'https://api.tvz.im/',
            'publicBaseUri'   => 'https://tvz.im/',
        ],
        'models'      => [
            'metadata' => [
                'adapter' => 'Memory'
            ]
        ]
    ]
);
