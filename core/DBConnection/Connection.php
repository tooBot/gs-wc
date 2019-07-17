<?php

namespace core;

use Pixie\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;

class DBConnection
{
    private $adapter;
    private $connection;

    public function __construct($server)
    {
        $config = Config::get($server);

        $dbConfig = [
            'driver'    => $config['driver'],
            'host'      => $config['host'],
            'database'  => $config['database'],
            'username'  => $config['username'],
            'password'  => $config['password'],
        ];

        $this->adapter = new Connection('mysql', $dbConfig);
    }

    public function getConnection()
    {
        try {
            $this->connection = new QueryBuilderHandler($this->adapter);
        } catch (\Exception $exception) {
            $this->connection = false;
        }

        return $this->connection;
    }
}