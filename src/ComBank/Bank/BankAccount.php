<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */


use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Bank\Contracts\BankAccountInterface;    
use ComBank\Exceptions\BankAccountException;


class BankAccount implements BankAccountInterface
{
    public const STATUS_OPEN = "OPEN";
    public const STATUS_CLOSED = "CLOSED";

    private string $name;
    private float $balance;
    private string $status;
    private bool $allowOverdraft;  
    private bool $closed;
    private ?OverdraftInterface $overdraft;

    public function __construct(string $name, float $balance, bool $allowOverdraft = false, bool $closed = false, string $status = self::STATUS_OPEN, ?OverdraftInterface $overdraft = null) {
        $this->name = $name;
        $this->balance = $balance;
        $this->allowOverdraft = $allowOverdraft;
        $this->closed = $closed;
        $this->status = $status;
        $this->overdraft = $overdraft;    
    }

    public function disableOverdraft(): void
    {
        $this->allowOverdraft = false;
        $this->overdraft = null;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function close(): void
        {
            $this->closeAccount(); 
        }


    public function closeAccount(): void
    {
        if ($this->isClosed()) {
            throw new BankAccountException("Account is already closed.");
        }
        $this->status = self::STATUS_CLOSED;
    }
    public function reopenAccount(): void
    {
        if ($this->isOpen()) {
            throw new BankAccountException("The account is already open.");
        }

        $this->status = self::STATUS_OPEN;
        $this->closed = false;
    }

    public function deposit(float $amount): void
    {
        if ($this->isClosed()) {
            throw new BankAccountException("Cannot deposit: account is closed.");
        }
        if ($amount <= 0) {
            throw new BankAccountException("Deposit amount must be positive.");
        }
        $this->balance += $amount;
    }

    public function debit(float $amount): void
    {
        if ($this->closed) {
            throw new BankAccountException("Cannot withdraw: account is closed.");
        }
        if ($amount <= 0) {
            throw new BankAccountException("Withdrawal amount must be positive.");
        }

        if (!$this->allowOverdraft && $amount > $this->balance) {
            throw new BankAccountException("Insufficient funds for withdrawal.");
        }

        if ($this->allowOverdraft && $amount > $this->balance) {
            if ($this->overdraft !== null) {
                $newBalance = $this->overdraft->apply($this->balance, $amount);
                if ($newBalance === false) {
                    throw new BankAccountException("Overdraft limit exceeded.");
                }
                $this->balance = $newBalance;
                return;
            }
        }

        $this->balance -= $amount;
    }

    public function transaction(BankTransactionInterface $transaction): void
    {
        if ($this->isClosed()) {
            throw new BankAccountException("Cannot perform transaction: account is closed.");
        }
        $this->balance = $transaction->applyTransaction($this);
    }
    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }
    public function getOverdraft(): OverdraftInterface
    {
        if ($this->overdraft === null) {
            throw new BankAccountException("No overdraft strategy is currently applied.");
        }
        return $this->overdraft;
    }
}
