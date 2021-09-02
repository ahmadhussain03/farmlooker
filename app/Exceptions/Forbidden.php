<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class Forbidden extends HttpException
{

    public function __construct(int $statusCode = 403, ?string $message = 'Forbidden.', \Throwable $previous = null)
    {
        parent::__construct($statusCode, $message, $previous);
    }
}
