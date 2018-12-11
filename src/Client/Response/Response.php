<?php

namespace Detail\Blitline\Client\Response;

use GuzzleHttp\Command\Guzzle\Operation;
use GuzzleHttp\Psr7\Response as PsrResponse;

interface Response
{
    public static function fromOperation(Operation $operation, PsrResponse $response): Response;

    public function getHttpResponse(): PsrResponse;

    public function toArray(): array;
}
