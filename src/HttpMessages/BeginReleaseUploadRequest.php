<?php

namespace BerryGoudswaard\HttpMessages;

use GuzzleHttp\Psr7\Request;

class BeginReleaseUploadRequest extends Request
{
    const URL_TEMPLATE = '/v0.1/apps/%s/%s/release_uploads';

    public function __construct($ownerName, $appName, $apiToken)
    {
        $uri = sprintf(
            self::URL_TEMPLATE,
            $ownerName,
            $appName
        );

        parent::__construct(
            'POST',
            $uri, 
            [
                'Content-Type' => 'application/json',
                'X-API-Token' => $apiToken,
            ]
        );
    }
}
