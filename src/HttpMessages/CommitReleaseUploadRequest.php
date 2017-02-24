<?php

namespace BerryGoudswaard\HttpMessages;

use GuzzleHttp\Psr7\Request;

class CommitReleaseUploadRequest extends Request
{
    const URL_TEMPLATE = '/v0.1/apps/%s/%s/release_uploads/%s';

    public function __construct($ownerName, $appName, $apiToken, $uploadId)
    {

        $uri = sprintf(
            self::URL_TEMPLATE,
            $ownerName,
            $appName,
            $uploadId
        );

        parent::__construct(
            'PATCH',
            $uri, 
            [
                'Content-Type' => 'application/json',
                'X-API-Token' => $apiToken,
            ],
            json_encode(['status' => 'committed'])
        );
    }
}
