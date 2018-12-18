<?php

namespace Detail\Blitline\Response;

class JobResponse extends ResourceResponse
{
    public function getJobId(): ?string
    {
        return $this->search('job_id');
    }

    public function getErrors(): array
    {
        $errors = $this->searchArray('errors');

        if (count($errors) > 0) {
            return array_values($errors);
        }

        $error = $this->search('error');
        $errors = [];

        if ($error !== null) {
            $errors[] = $error;
        }

        return $errors;
    }

    public function hasErrors(): bool
    {
        return count($this->getErrors()) > 0;
    }
}
