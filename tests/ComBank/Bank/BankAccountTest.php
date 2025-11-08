<?php

use PHPUnit\Framework\TestCase;
use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;

class BankAccountTest extends TestCase
{



    public function testInitialBalanceIsSetCorrectly(): void
    {
        $account = new BankAccount(name: 'TestAccount1', balance: 100.0); 
        $this->assertEqualsWithDelta(100.0, $account->getBalance(), 0.001);
    }

    public function testDepositTransaction(): void
    {   
        $bankAccount = new BankAccount(name: 'TestAccount2', balance: 200.0);
        $bankAccount->transaction(new DepositTransaction(30.0));
        $this->assertEqualsWithDelta(230.0, $bankAccount->getBalance(), 0.001);
    }

    public function testWithdrawTransaction(): void
    {
        $bankAccount = new BankAccount(name: 'TestAccount3', balance: 200.0);
        $bankAccount->transaction(new WithdrawTransaction(150.0));
        $this->assertEqualsWithDelta(50.0, $bankAccount->getBalance(), 0.001);
    }
    public function testCannotReopenOpenAccount(): void
    {
        $this->expectException(BankAccountException::class);

        $account = new BankAccount(name: 'TestAccount4', balance: 100.0);
        $account->reopenAccount(); 
    }

    public function testCanCloseAndReopenAccount(): void
    {
        $account = new BankAccount(name: 'TestAccount5', balance: 100.0);
        $account->closeAccount();
        $this->assertFalse($account->isOpen());

        $account->reopenAccount();
        $this->assertTrue($account->isOpen());
    }

    public function testWithdrawWithOverdraft(): void
    {
        $bankAccount = new BankAccount(name: 'TestAccount6', balance: 250.0);
        $bankAccount->applyOverdraft(new SilverOverdraft());
        $bankAccount->transaction(new WithdrawTransaction(300.0));

        $this->assertEqualsWithDelta(-50.0, $bankAccount->getBalance(), 0.001);
    }

    public function testFailedTransactionWithOverdraft(): void
    {
        $this->expectException(FailedTransactionException::class);

        $bankAccount = new BankAccount(name: 'TestAccount7', balance: 100.0);
        $bankAccount->applyOverdraft(new SilverOverdraft());
        $bankAccount->transaction(new WithdrawTransaction(201.0));
    }

    public function testTransactionAfterAccountClosed(): void
    {
        $this->expectException(BankAccountException::class);

        $bankAccount = new BankAccount(name: 'TestAccount8', balance: 100.0);
        $bankAccount->closeAccount();

        $bankAccount->transaction(new DepositTransaction(50.0)); 
    }
}
