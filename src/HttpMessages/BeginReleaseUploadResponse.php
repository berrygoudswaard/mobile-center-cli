<?php

namespace BerryGoudswaard\HttpMessages;

use Psr\Http\Message\ResponseInterface;

class BeginReleaseUploadResponse
{
    private $response;
    private $decodedJson;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->decodedJson = json_decode($response->getBody()->getContents());
    }

    public function getUploadId()
    {
        return $this->decodedJson->upload_id;
    }

    public function getUploadUrl()
    {
        return $this->decodedJson->upload_url;
    }
}
