<?php

namespace DetailTest\Blitline\Client\Response;

use Detail\Blitline\Client\Response\BaseResponse;

class BaseResponseTest extends ResponseTestCase
{
    public function provideErrors(): array
    {
        return [
            [
                ['error' => 'error #1'],
                'error #1',
            ],
            [
                ['errors' => ['error #2']],
                'error #2',
            ],
            [
                ['errors' => ['error #3', 'error #4']],
                'error #3',
            ],
            [
                ['errors' => ['error_5_key' => 'error #5', 'error_6_key' => 'error #6']],
                'error #5',
            ],
        ];
    }

    public function testToArray(): void
    {
        $data = ['key' => 'value'];
        $response = $this->getBaseResponse($data);

        $this->assertEquals(['results' => $data], $response->toArray());
    }

    protected function getBaseResponse(array $data): BaseResponse
    {
        /** @var BaseResponse $response */
        $response = $this->getResponse(BaseResponse::CLASS, $data, true);

        return $response;
    }
}
