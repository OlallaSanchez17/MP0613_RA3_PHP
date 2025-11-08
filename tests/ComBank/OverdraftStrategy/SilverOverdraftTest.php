<?php
use ComBank\OverdraftStrategy\SilverOverdraft;
use PHPUnit\Framework\TestCase;


/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:05 PM
 */

class SilverOverdraftTest extends TestCase
{

    /**
     * @return array;
     * */
    public function newAmountsProvider()
    {
        return [
            [50,true],
            [-50,true],
            [-100,true],
            [-101,false]
        ];
    }
}