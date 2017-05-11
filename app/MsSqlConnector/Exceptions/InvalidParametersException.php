<?php

namespace Sage\MsSqlConnector\Exceptions;

use Exception;

class InvalidParametersException extends Exception
{
    protected $message = 'Invalid parameters, you should pass an array of credentials.';
}
