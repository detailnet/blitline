<?php

namespace Detail\Blitline\Response;

interface ResponseInterface
{
    /**
     * @param string $key
     * @return array|mixed
     */
    public function getResult($key = null);

    /**
     * @return boolean
     */
    public function isSuccess();

    /**
     * @return boolean
     */
    public function isError();

    /**
     * @return string|null
     */
    public function getError();
}
