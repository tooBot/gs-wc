<?php

namespace core;

class Logs
{
    private $repository;

    public function __construct()
    {
        $this->repository = new LogsRepository();
    }

    public function addRecord($data)
    {
        $this->repository->addRecord($data);
    }

    public function getAllRecords()
    {
        return $this->repository->getAllRecords();
    }

    public function getLogsFromEvgeska()
    {
        return $this->repository->getLogsFromEvgeska();
    }

    public function getLogsByClanId($clanId)
    {
        $result = [];
        $logs = $this->repository->getLogsByClanId($clanId);

        foreach ($logs as $log) {
            $result[] = $log->log_time;
        }

        return $result;
    }

    public function logWork()
    {
        $workedLogs = $this->prepareWorkedLogs($this->getAllRecords());
        $evgeskaLogs = $this->getLogsFromEvgeska();
        $participants = new Participants();

        $analyzer = new Analyzer();

        foreach ($evgeskaLogs as $data) {
            preg_match_all('#log\/\#id(\d+)\/#i', $data->log, $matches);
            $fightId = $matches[1][0];

            if (!$fightId) {
                preg_match_all('#log\/\#id(\d+)\/page1#i', $data->log, $matches);
                $fightId = $matches[1][0];
            }

            $clanId = $data->clanA;

            if (!is_null($fightId)) {
                if (!in_array($fightId, $workedLogs)) {
                    $participantsData = $analyzer->parseXML($fightId);

                    $fightStartTime = $participantsData['time'];

                    unset($participantsData['time']);

                    $participants->updateRecord($this->prepareParticipantsData($participantsData));
                    $this->addRecord([
                        'log_clan' => $clanId,
                        'log_id' => $fightId,
                        'log_time' => $fightStartTime
                    ]);
                }
            }
        }
    }

    public function prepareWorkedLogs($logs)
    {
        $result = [];

        foreach ($logs as $log) {
            $result[] = $log->log_id;
        }

        return $result;
    }

    public function prepareParticipantsData($participants)
    {
        $result = [];

        foreach ($participants['participants'] as $heroId => $data) {
            $result[$heroId] = [
                'h_part' => (int)$data['part'],
                'h_bless' => (int)$data['bless']
            ];
        }

        return $result;
    }

}