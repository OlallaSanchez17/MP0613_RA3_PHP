<?php namespace ComBank\OverdraftStrategy\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:44 PM
 */

interface OverdraftInterface
{
    
    public function isGrantOverdraftFunds(float $amount): bool;
    public function getOverdraftFundsAmount(): float;
    public function apply(float $currentBalance, float $amountToWithdraw): float|bool; 
    public function getLimit(): float;  
}