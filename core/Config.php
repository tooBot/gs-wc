<?php

namespace core;

class Config
{
    private static $config = [
        'apiKey' => '89210d6829',

        'dbDev' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'wc',
            'username'  => 'root',
            'password'  => '',
        ],

        'dbProd' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'host1309088',
            'username'  => 'host1309088_ff25',
            'password'  => 'cduVMtf231',
        ]
    ];

    public static function get($name)
    {
        return self::$config[$name];
    }
}