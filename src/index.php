<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    $noOverdraft = new NoOverdraft();

    $bankAccount1 = new BankAccount(
        name: "bankAccount1",
        balance: 400.0,
        allowOverdraft: false,
        status: BankAccount::STATUS_OPEN,
        overdraft: $noOverdraft 
    );
    pl('My balance: ' . $bankAccount1->getBalance());
    // close account
    $bankAccount1->close(); 
    pl("My account is now closed.\n");
    $bankAccount1->reopenAccount();
    pl("My account is now reopened.\n");

    // deposit +150
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new DepositTransaction(150.0));
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
        $bankAccount1->transaction(new WithdrawTransaction(25));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
        $bankAccount1->transaction(new WithdrawTransaction(600));
    pl('My new balance after withdrawal (-600) : ' . $bankAccount1->getBalance());


 
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());


  
//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft 200.0 funds] --------');
try {
    $silverOverdraft = new SilverOverdraft(overdraftLimit: 100.0);

    $bankAccount2 = new BankAccount(
        name: "bankAccount2",
        balance: 200.0,
        allowOverdraft: true,
        status: BankAccount::STATUS_OPEN,
        overdraft: $silverOverdraft 
    );

    pl ('My balance : ' . $bankAccount2->getBalance());
        $bankAccount2->close();

        $bankAccount2->reopenAccount();

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
        $bankAccount2->transaction(new DepositTransaction(100));
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
        $bankAccount2->transaction(new WithdrawTransaction(300));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());
 
    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
        $bankAccount2->transaction(transaction: new WithdrawTransaction(50));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());
 
    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
        $bankAccount2->transaction(transaction: new WithdrawTransaction(120));
    pl('My new balance after withdrawal (-120) with funds : ' . $bankAccount2->getBalance());


    
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
        $bankAccount2->transaction(transaction: new WithdrawTransaction(20));

} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());


try {
   
} catch (BankAccountException $e) {
    pl($e->getMessage());
}
    pl('My balance: ' . $bankAccount2->getBalance());
    // close account
    $bankAccount2->close(); 
    pl("My account is now closed.\n");

