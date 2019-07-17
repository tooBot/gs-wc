<?php

namespace core;

class Participants extends AbstractClient
{
    private $repository;

    public $config = [
        'clans' => [
            80, 191, 64, 42, 126, 56, 288, 341, 128, 78, 138, 37, 114, 327, 90, 279, 170, 40, 24, 46, 216, 45, 36, 137,
            142, 106, 102, 82, 31, 85, 311, 68, 232, 141, 150, 27, 43, 79, 123, 73, 352, 111, 96, 125, 234, 32, 345, 97,
            346, 101, 275, 91, 76, 83, 39, 57, 59, 222, 348, 38, 205, 115, 273, 47, 257, 58, 186, 294, 53, 342, 307, 139, 248
        ]
    ];

    public function __construct()
    {
        parent::__construct();

        $this->repository = new ParticipantsRepository();
    }

    public function getParticipants($clanId)
    {
        $path = 'https://www.ereality.ru/clan' . $clanId . '.html';
        $pageSource = parent::getPageSource($path);

        preg_match_all('@\/info(\d+)@', $pageSource, $matches);
        $result = $matches[1];

        return array_unique($result);
    }

    public function addParticipants()
    {
        foreach ($this->config['clans'] as $clanId) {
            $participants = $this->getParticipants($clanId);

            if (count($participants)) {
                $heroClient = new HeroInfo();
                foreach ($participants as $participant) {
                    $participantData = $heroClient->getBasicInfo($participant);

                    $this->repository->addRecord([
                        'h_id' => $participantData['h_id'],
                        'h_name' => $participantData['h_name'],
                        'c_id' => $participantData['c_id'],
                        'h_last_visit' => $participantData['h_last_visit']
                    ]);
                }
            }
        }
    }

    public function updateRecord($participants)
    {
        foreach ($participants as $heroId => $data) {
            $this->repository->updateRecord($heroId, $data);
        }
    }

    public function getRecordsByClanId($clanId)
    {
        $result = [];

        $participants = $this->repository->getRecordsByClanId($clanId);

        foreach ($participants as $player) {
            $result[] = [
                'id' => $player->h_id,
                'name' => $player->h_name,
                'part' => (bool)$player->h_part,
                'bless' => (bool)$player->h_bless,
                'last_visit' => $player->h_last_visit
            ];
        }

        return $result;
    }
}