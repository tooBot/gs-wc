<?php

namespace core;

class Analyzer
{
    const XML_REQUEST_PATH = 'https://www.ereality.ru/log';
    const FUCKING_INVISER = 'Невидимка';

    private function getFullPath($logId, $page)
    {
        return self::XML_REQUEST_PATH . $logId . '/page' . $page . '.xml';
    }

    public function getXMLData($logId, $page)
    {
        $path = $this->getFullPath($logId, $page);

        $curl = curl_init($path);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function parseXML($logId)
    {
        $result = [];
        $page = 1;

        $heroInfoService = new HeroInfo();

        while (!isset($result['result']) || !isset($result['error'])) {
            $xml = $this->getXMLData($logId, $page);
            $xmlData = new \SimpleXMLElement($xml);

            foreach (($xmlData->xpath('//start')) as $log) {
                $result['time'] = $log['time'];

                foreach ($log->player as $player) {
                    $playerData = Helpers::xml2array($player);
                    if ($playerData['id']) {
                        $result['participants'][$playerData['id']] = ['part' => true, 'bless' => false];
                    }
                }
            }

            foreach ($xmlData->xpath('//bless') as $key => $log) {
                if ($log['ok'] == 1) {
                    $hero = explode(';', $log['p1']);
                    $heroData = $heroInfoService->getBasicInfo($hero[0]);

                    $result['participants'][$heroData['h_id']] = ['part' => false, 'bless' => true];
                }
            }

            $battleResult = $xmlData->xpath('//results');
            if ($battleResult) {

                return $result;
            }

            $page++;
        }

        return $result;
    }

    public function isBot($data) {
        if ($data[1] == 1 && $data[2] == 0 && $data[3] == 0) {

            return true;
        }

        return false;
    }

    public function debugLogs($logId)
    {
        $result = [];
        $page = 1;

        while(!isset($result['result'])) {
            $xml = $this->getXMLData($logId, $page);
            $xmlData = new \SimpleXMLElement($xml);

            foreach (($xmlData->xpath('//start')) as $log) {
                foreach ($log->player as $player) {
                    $playerData = Helpers::xml2array($player);
                    if ($playerData['id']) {
                        $result['participants'][$playerData['id']][] = ['part' => true, 'bless' => false];
                    }
                }
            }

            $battleResult = $xmlData->xpath('//results');
            if ($battleResult) {
                $result['result'] = $battleResult;
            }

            $page++;
        }

        return $result;
    }

}