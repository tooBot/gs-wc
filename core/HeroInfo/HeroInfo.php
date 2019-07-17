<?php

namespace core;

class HeroInfo extends AbstractClient
{
    public function getBasicInfo($hero)
    {
        $result = [];

        $apiKey = Config::get('apiKey');
        $path = 'http://api.ereality.ru/' . $apiKey . '/pinfo/?' . (is_numeric($hero) ? 'h_id=' : 'h_name=') . $hero;
        $pageSource = parent::getPageSource($path);
        $heroData = unserialize($pageSource);

        if(isset($heroData['info'])) {
            $result = [
                'h_id' => $heroData['info']['id'],
                'h_name' => $heroData['info']['h_name'],
                'c_id' => $heroData['info']['c_id'],
                'h_last_visit' => $heroData['info']['u_last_visit']
            ];
        }

        return $result;
    }
}