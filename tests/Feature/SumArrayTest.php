<?php

namespace Tests\Feature;

use Tests\TestCase;
use Carbon\Carbon;

class SumArrayTest extends TestCase
{
  // ./vendor/phpunit/phpunit/phpunit tests/Feature/SumArrayTest.php --group Feature/SumArrayTest
    /**
     * @group Feature/SumArrayTest
     */
    public function testSumArray()
    {
      // select date_format(日期A, "%Y-%m-%d") as 日期A_format, sum(項目A) as 項目A總和, sum(項目B) as 項目B總和, 類型 from 表A
      // where 流水號 = ? 
      // and 日期A >= ? 
      // and 日期B <= ? 
      // and not (日期A =  
      // (select MAX(日期A) from 表A where 流水號 = ?  and date_format(日期A, "%Y-%m-%d") = 2024-03-23) 
      // and 項目A + 項目B < 1000)
      // group by date_format(日期A, "%Y-%m-%d"), 類型
      $mock_DB_result = [
        [],
        []
      ];
    }
}
