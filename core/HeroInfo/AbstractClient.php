<?php

namespace core;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

abstract class AbstractClient
{
    const REQUEST_TIMEOUT = 200;

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendRequest($request)
    {
        $result = [
            'ok' => 0,
            'data' => ''
        ];

        try {
            $data = $this->client->send($request, ['timeout' => self::REQUEST_TIMEOUT]);
            $result['ok'] = $data->getStatusCode();
            $result['data'] = $data->getBody();
        } catch (\Exception $exception) {
            $result['ok'] = $exception->getCode();
        }

        return $result;
    }

    public function getPageSource($path)
    {
        $request = new Request('GET', $path);
        $data = $this->sendRequest($request);

        return (string)$data['data'];
    }
}