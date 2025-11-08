<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Exceptions\BankAccountException;



class WithdrawTransaction implements BankTransactionInterface  
{
    private float $amount;
    protected string $transactionId;

    protected \DateTimeImmutable $timestamp;

    public function __construct(float $amount)
    {
        if ($amount <= 0) {
            throw new ZeroAmountException("The withdrawal amount must be greater than zero.");
        }

        $this->amount = $amount;
        $this->transactionId = uniqid('txn_', true);
        $this->timestamp = new \DateTimeImmutable(); 
    }

    /**
     * Contiene TODA la lógica de retiro, reemplazando BankAccount::debit().
     * @param BankAccountInterface $account
     * @return float El nuevo saldo.
     * @throws BankAccountException
     */
    public function applyTransaction(BankAccountInterface $account): float
    {
        if ($account->isClosed()) {
             throw new BankAccountException("The withdrawal cannot be made: the account is closed.");
        }
        
        $currentBalance = $account->getBalance();
        $amount = $this->amount;


        if ($amount <= $currentBalance) {
            return $currentBalance - $amount;
        }

        try {
            $overdraft = $account->getOverdraft();

            $newBalance = $overdraft->apply($currentBalance, $amount);
            
            if ($newBalance === false) {
                throw new BankAccountException("Overdraft limit exceeded.");
            }
            
            return $newBalance;

        } catch (BankAccountException $e) {

            if (str_contains($e->getMessage(), "No overdraft strategy")) {
                throw new BankAccountException("Insufficient funds for withdrawal.");
            }
            throw $e;
        }
    }
    
    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransactionInfo(): string
    {
        return sprintf(
            "WITHDRAWAL | ID: %s | Amount: $%.2f | Date: %s",
            $this->transactionId,
            $this->amount,
            $this->timestamp->format('Y-m-d H:i:s')
        );
    }
}