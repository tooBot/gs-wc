<?php

namespace core;

class ParticipantsRepository
{
    const TBL_MAIN = 'gs_wc_participants';

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

    public function updateRecord($heroId, $data)
    {
        $this->connection
            ->table(self::TBL_MAIN)
            ->where('h_id', $heroId)
            ->update($data);
    }

    public function getRecordsByClanId($clanId)
    {
        return $this->connection
            ->table(self::TBL_MAIN)
            ->where('c_id', $clanId)
            ->get();
    }

}