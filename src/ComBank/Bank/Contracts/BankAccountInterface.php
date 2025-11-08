<?php namespace ComBank\Bank\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:26 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

interface BankAccountInterface
{
    public function getBalance(): float;

    const STATUS_OPEN = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';
    public function debit(float $amount): void;    
    public function isClosed(): bool;
    public function closeAccount(): void;
    public function close(): void;
    public function isOpen(): bool;
    public function reopenAccount(): void;
    public function getOverdraft(): OverdraftInterface;
    public function applyOverdraft(OverdraftInterface $overdraft): void;
    public function getStatus(): string;
    public function setBalance(float $balance): void;

    public function transaction(BankTransactionInterface $transaction): void;
}
