<?php namespace ComBank\Exceptions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:33 AM
 */

abstract class BaseExceptions extends \Exception
{
    protected $errorCode = 0;
    protected $errorLabel = 'BaseExceptions';

    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}