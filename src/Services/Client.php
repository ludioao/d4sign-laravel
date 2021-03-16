<?php

namespace Edmarr2\D4sign\Services;

use Edmarr2\D4sign\Exceptions\InvalidCryptKeyException;
use Edmarr2\D4sign\Exceptions\InvalidTokenException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

abstract class Client
{
    protected $client;

    public function __construct()
    {
        if (!config('d4sign.token_api')) {
            throw new InvalidTokenException();
        }

        if (!config('d4sign.crypt_key')) {
            throw new InvalidCryptKeyException();
        }
        $this->client = new GuzzleClient([
            'base_uri' => config('d4sign.base_uri'),
            'headers' => [
                'Accept' => 'application/json',
                'tokenAPI' => config('d4sign.token_api'),
                'cryptKey' => config('d4sign.crypt_key')
            ]
       ]);
    }

    public function get(string $url, array $query = [])
    {
        try {
            return $this->client->get($url, $query)->getBody()->getContents();
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public function post(string $url, array $data = [])
    {
        return $this->client->post($url, $data)->getBody()->getContents();
    }

    public function put(string $url, array $data)
    {
        return $this->client->put($url, $data)->getBody()->getContents();
    }

    public function delete(string $url, array $data)
    {
        return $this->client->delete($url, $data)->getBody()->getContents();
    }
}