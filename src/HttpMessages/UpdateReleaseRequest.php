<?php

namespace BerryGoudswaard\HttpMessages;

use GuzzleHttp\Psr7\Request;

class UpdateReleaseRequest extends Request
{
    public function __construct($ownerName, $appName, $apiToken, $uri)
    {
        parent::__construct(
            'PATCH',
            $uri, 
            [
                'Content-Type' => 'application/json',
                'X-API-Token' => $apiToken,
            ],
            json_encode(['distribution_group_name' => 'Collaborators'])
        );
    }
}
