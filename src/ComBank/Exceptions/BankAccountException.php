<?php 
namespace ComBank\Exceptions;

use ComBank\Exceptions\BaseExceptions;
/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:29 PM
 */

class BankAccountException extends BaseExceptions
{
    protected $errorCode = 500;
    protected $errorLabel = 'BankAccountException';

    public function __construct(string $message = "Bank account error", int $code = 0)
    {
        parent::__construct($message, $code);
    }
    public function __toString(): string
    {
        return "[{$this->errorLabel}] {$this->getMessage()}";
    }
}