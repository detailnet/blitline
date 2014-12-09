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
            'uri'              => 'job',
            'summary'          => 'Submitting a job',
            'documentationUrl' => 'http://www.blitline.com/docs/api',
            'parameters'       => array(
                'src' => array(
                    'description' => 'The location of the image you wish to process',
                    'location'    => 'json',
                    'type'        => 'string',
                    'required'    => true,
                ),
                /** @todo Define functions properly and remove "additionalParamters" as catch-all... */
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
        ),
    ),
);
