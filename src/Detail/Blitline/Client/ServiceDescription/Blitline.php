<?php

use Detail\Blitline\Response;

return array(
    'name'        => 'Blitline',
    'description' => 'Image processing in the cloud',
    'operations'  => array(
        'pollJob' => array(
            'httpMethod'       => 'GET',
            'uri'              => 'https://cache.blitline.com/listen/{job_id}',
            'summary'          => 'Polling for a job',
            'documentationUrl' => 'http://www.blitline.com/docs/polling',
            'parameters'       => array(
                'job_id' => array(
                    'description' => 'The ID of the job you wish to poll',
                    'location'    => 'uri',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
            'responseClass' => Response\JobProcessed::CLASS,
            'data' => array(
                'requestOptions' => array('timeout' => 60 * 10), // Wait 10 minutes for the completion of a job
            ),
        ),
        'submitJob' => array(
            'httpMethod'       => 'POST',
            'uri'              => 'job',
            'summary'          => 'Submitting a job',
            'documentationUrl' => 'http://www.blitline.com/docs/api',
            'parameters'       => array(
                'src' => array(
                    'description' => 'The S3 location of the image you wish to process',
                    'location'    => 'json',
                    'type'        => 'array',
                    'required'    => true,
                ),
                /** @todo Define functions properly and remove "additionalParameters" as catch-all... */
//                'functions' => array(
//                    'description' => 'One or more operations you want performed on the source image',
//                    'location'    => 'json',
//                    'type'        => 'array',
//                    'required'    => true,
//                ),
            ),
            'additionalParameters' => array(
                'location' => 'json',
            ),
            'responseClass' => Response\JobSubmitted::CLASS,
        ),
    ),
);
