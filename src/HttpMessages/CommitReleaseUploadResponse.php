<?php

namespace BerryGoudswaard\HttpMessages;

use Psr\Http\Message\ResponseInterface;

class CommitReleaseUploadResponse
{
    private $response;
    private $decodedJson;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->decodedJson = json_decode($response->getBody()->getContents());
    }

    public function getReleaseUrl()
    {
        return $this->decodedJson->release_url;
    }

    public function getPackageUrl()
    {
        return $this->decodedJson->package_url;
    }
}
