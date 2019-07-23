<?php

namespace Charcoal\Admin\Guide\Service\Api\Youtube;

use GuzzleHttp\Client;

class AbstractYoutubeService
{

    const GOOGLE_API_SCOPE = 'https://www.googleapis.com/youtube/v3/';

    /**
     * @var
     */
    protected $apiKey;

    /**
     * @var mixed
     */
    protected $baseUrl;

    /**
     * AbstractYoutubeService constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->setApiKey($data['key']);

        if ($data['base-url']) {
            $this->setBaseUrl($data['base-url']);
        }
    }


    /**
     * Created at https://console.cloud.google.com
     *
     * @return mixed
     */
    public function apiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     * @return AbstractYoutubeService
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function baseUrl()
    {
        if (!$this->baseUrl) {
            throw new \RuntimeException(
                'Base URL is required to run the youtube service.'
            );
        }
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return AbstractYoutubeService
     */
    public function setBaseUrl($baseUrl)
    {
        $baseUrl       = rtrim($baseUrl, '/') . '/';
        $this->baseUrl = $baseUrl;
        return $this;
    }


    /**
     * @param $url
     * @return bool
     */
    protected function fetch($url)
    {
        $url    = ltrim($url, '/');
        $client = new Client();
        try {
            $res = $client->request('GET', $url);
        } catch (\Exception $e) {
            return false;
        }

        if ($res->getStatusCode() !== 200) {
            return false;
        }

        return $res;
    }
}
