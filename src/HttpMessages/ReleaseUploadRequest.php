<?php

namespace BerryGoudswaard\HttpMessages;

use GuzzleHttp\Psr7\Request;

class ReleaseUploadRequest extends Request
{
    private $options = [];
    public function __construct($uri, $file)
    {
        $this->options = [
            'multipart' => [
                [
                    'name'     => 'ipa',
                    'contents' => fopen($file, 'r'),
                    'filename' => basename($file) 
                ]
            ]
        ];

        parent::__construct(
            'POST',
            $uri
        );
    }

    public function getOptions()
    {
        return $this->options;
    }
}
