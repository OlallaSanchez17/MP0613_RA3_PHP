<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\FailedTransactionException;

class DepositTransaction implements BankTransactionInterface
{
    private float $amount;

    public function __construct(float $amount)
    {
        if ($amount <= 0) {
        throw new ZeroAmountException("Invalid amount: $amount");
        }
        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $account): float
    {
        return $account->getBalance() + $this->amount;
    }
    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransactionInfo(): string 
    {
        return 'DEPOSIT_TRANSACTION';

    }

}
