<?php

use Detail\Blitline\Response;

return [
    'name'        => 'Blitline',
    'description' => 'Image processing in the cloud',
    'operations'  => [
        'pollJob' => [
            'httpMethod'       => 'GET',
            'uri'              => 'https://cache.blitline.com/listen/{job_id}',
            'summary'          => 'Polling for a job',
            'documentationUrl' => 'http://www.blitline.com/docs/polling',
            'parameters'       => [
                'job_id' => [
                    'description' => 'The ID of the job you wish to poll',
                    'location'    => 'uri',
                    'type'        => 'string',
                    'required'    => true,
                ],
            ],
            'responseModel' => Response\JobProcessed::CLASS,
            'data' => [
                'http' => ['timeout' => 60 * 10], // Wait 10 minutes for the completion of a job
            ],
        ],
        'submitJob' => [
            'httpMethod'       => 'POST',
            'uri'              => 'job',
            'summary'          => 'Submitting a job',
            'documentationUrl' => 'http://www.blitline.com/docs/api',
            'parameters'       => [
                'src' => [
                    'description' => 'The location of the image you wish to process, URL or AWS S3 bucket/key',
                    // Documented usage of the AWS S3 is only in "Blitline Example Jobs" (http://docs.blitline.com/articles/examples.html):
                    // "Using the permissions you’ve given Blitline for sources: By default, Blitline assumes a source is public.
                    // If you instead want to have Blitline read OUT of your private bucket, you will need to change how the “src”
                    // is described. This is an example of using an “s3” source bucket and key"
                    // Link: //https://www.blitline.com/v3/home/gist?gist_id=3492389
                    'location'    => 'json',
                    'type'        => ['string', 'array'],
                    'required'    => true,
                ],
                /** @todo Define functions properly and remove "additionalParameters" as catch-all... */
//                'functions' => array(
//                    'description' => 'One or more operations you want performed on the source image',
//                    'location'    => 'json',
//                    'type'        => 'array',
//                    'required'    => true,
//                ),
            ],
            'additionalParameters' => [
                'location' => 'json',
            ],
            'responseModel' => Response\JobSubmitted::CLASS,
        ],
    ],
];
