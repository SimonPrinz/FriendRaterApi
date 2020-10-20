<?php

namespace App\Subscribers;

use Exception;
use Throwable;

class ApiException extends Exception
{
    /** @var Throwable[] */
    private $errors;

    /**
     * @param Throwable[] $errors
     * @param int   $code
     */
    public function __construct(array $errors, int $code = 500)
    {
        parent::__construct();

        $this->errors = $errors;
        $this->code = $code;
    }

    /** @return Throwable[] */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
