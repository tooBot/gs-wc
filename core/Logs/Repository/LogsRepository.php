<?php

namespace core;

class LogsRepository
{
    const TBL_MAIN = 'gs_wc_logs_done';

    private $adapter;
    private $connection;

    public function __construct()
    {
        //не забыть поправить при заливке
        $this->adapter = new DBConnection('dbDev');
        $this->connection = $this->adapter->getConnection();
    }

    public function addRecord($data)
    {
        $this->connection
            ->table(self::TBL_MAIN)
            ->insert($data);
    }

    public function getLogsFromEvgeska()
    {
        return $this->connection
            ->table('evgeska_2019_table')
            ->get();
    }

    public function getAllRecords()
    {
        return $this->connection
            ->table(self::TBL_MAIN)
            ->get();
    }

    public function getLogsByClanId($clanId)
    {
        return $this->connection
            ->table(self::TBL_MAIN)
            ->where('log_clan', $clanId)
            ->get();
    }
}