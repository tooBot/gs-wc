<?php

namespace core;

class Config
{
    private static $config = [
        'apiDevKey' => '89210d6829',
        'apiProdKey' => '8e9f7d57c1',

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