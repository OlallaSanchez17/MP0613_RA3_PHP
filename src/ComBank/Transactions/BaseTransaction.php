<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:24 PM
 */

use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Support\Traits\AmountValidationTrait;

abstract class BaseTransaction
{
    protected float $amount;

    public function __construct(float $amount)
    {
        if($amount<=0){
            throw new ZeroAmountException($e->getMessage());
        }
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
