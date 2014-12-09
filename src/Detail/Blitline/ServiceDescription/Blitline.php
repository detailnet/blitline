<?php

return array(
    'name'        => 'Blitline',
    'description' => 'Image processing in the cloud',
    'operations'  => array(
        'pollJob' => array(
            'httpMethod'       => 'GET',
            'uri'              => 'https://cache.blitline.com/listen/{jobId}',
            'summary'          => 'Polling for a job',
            'documentationUrl' => 'http://www.blitline.com/docs/polling',
            'parameters'       => array(
                'jobId' => array(
                    'description' => 'The ID of the job you wish to poll',
                    'location'    => 'uri',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
        ),
        'postJob' => array(
            'httpMethod'       => 'POST',
            'uri'              => 'jobs',
            'summary'          => 'Submitting a job',
            'documentationUrl' => 'http://www.blitline.com/docs/api',
            'parameters'       => array(
//                'application_id' => array(),
                'src' => array(
                    'description' => 'The location of the image you wish to process',
                    'location'    => 'json',
                    'type'        => 'string',
                    'required'    => true,
                ),
                'functions' => array(
                    'description' => 'One or more operations you want performed on the source image',
                    'location'    => 'json',
                    'type'        => 'array',
                    'required'    => true,
                ),
            ),
        ),
    ),
);
