<?php

namespace Detail\Blitline\Response;

abstract class JobResponse extends BaseResponse
{
    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->getResult('job_id');
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        $errors = $this->getArrayResult('errors');

        if (count($errors) > 0) {
            return array_values($errors);
        }

        $error = $this->getResult('error');
        $errors = [];

        if ($error !== null) {
            $errors[] = $error;
        }

        return $errors;
    }

    /**
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }

    /**
     * @return string|null
     * @deprecated Use getErrors()
     */
    public function getError()
    {
        $errors = $this->getErrors();

        if (count($errors) > 0) {
            return $errors[0];
        }

        return null;
    }

    /**
     * @return boolean
     * @deprecated Use hasErrors()
     */
    public function hasError()
    {
        return $this->hasErrors();
    }
}
