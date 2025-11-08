<?php namespace ComBank\OverdraftStrategy;

    use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

class NoOverdraft implements OverdraftInterface
{
private const OVERDRAFT_LIMIT = 0.0;
public function getOverdraftFundsAmount():float
{
    return self::OVERDRAFT_LIMIT;   
}
public function isGrantOverdraftFunds(float $newAmount): bool
{
    return ($this->getOverdraftFundsAmount() + $newAmount) >= 0;
}

public function getLimit(): float
    {
        return 0.0; 
    }
public function apply(float $currentBalance, float $amountToWithdraw): float|bool
    {
    if ($amountToWithdraw > $currentBalance) {
        return false;
    }
       
    return $currentBalance - $amountToWithdraw;
    }


   
}
