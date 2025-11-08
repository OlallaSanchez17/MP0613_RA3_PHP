<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
class SilverOverdraft implements OverdraftInterface
{
    private float $overdraftLimit;

    public function __construct(float $overdraftLimit = -100.0)
    {
        $this->overdraftLimit = $overdraftLimit < 0 ? $overdraftLimit : -abs($overdraftLimit);
    }

    public function getOverdraftFundsAmount(): float
    {
        return $this->overdraftLimit;
    }

    public function isGrantOverdraftFunds(float $newAmount): bool
    {
        $newBalance = $currentBalance - $requestedAmount;
        return $newBalance >= $this->overdraftLimit;
    }
public function apply(float $currentBalance, float $amountToWithdraw): float|bool
    {
        $newBalance = $currentBalance - $amountToWithdraw;
        
        $maxOverdraft = $this->overdraftLimit * -1; 

        if ($newBalance < $maxOverdraft) {
            return false; 
        }
        
        return $newBalance;
    }
    public function getLimit(): float
    {
        return $this->overdraftLimit;
    }
}