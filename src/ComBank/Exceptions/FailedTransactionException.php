<?php namespace ComBank\Exceptions;

use ComBank\Transactions\Contracts\BankTransactionInterface;
/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:20 PM
 */

class FailedTransactionException extends BaseExceptions
{
    protected $errorCode = 401;
    protected $errorLabel = 'FailedTransactionException';

    private ?BankTransactionInterface $transaction;

    public function __construct(
        string $message = "Transaction failed",
        int $code = null,
        ?BankTransactionInterface $transaction = null,
        \Throwable $previous = null
    ) {
        $this->transaction = $transaction;
        parent::__construct($message, $code ?? $this->errorCode, $previous);
    }

    public function getTransaction(): ?BankTransactionInterface
    {
        return $this->transaction;
    }
}