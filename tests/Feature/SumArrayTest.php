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
      // 假設這是大圈取得的資訊
      $rid = '24t1q26gquirgqa78g';
      $eff_time = '2024-01-01 00:00:00';
      $exp_time = '2023-01-05 23:59:00';
      $exp_day = '2023-01-05'; // 實務上應該是用Carbon轉換$exp_time

      // $mock_DB_result = FakeModel::where('rid',$rid)->where('eff_time', '>=', $eff_time)->where('exp_time', '<=', $exp_time)->get()->toArray();
      $mock_DB_result = [
        ['rid' => '24t1q26gquirgqa78g',
          'start_time' => '2024-01-01 00:20:05',
          'end_time' => '2023-01-01 16:31:57',
          'col_x' => 123,
          'col_y' => 5678,
          'type' => '12355'],
        ['rid' => '24t1q26gquirgqa78g',
          'start_time' => '2024-01-01 20:07:05',
          'end_time' => '2023-01-01 23:31:57',
          'col_x' => 500,
          'col_y' => 599,
          'type' => '12355'],
        ['rid' => '24t1q26gquirgqa78g',
          'start_time' => '2024-01-02 00:20:05',
          'end_time' => '2023-01-20 01:31:57',
          'col_x' => 200,
          'col_y' => 6000,
          'type' => '12355'],
        ['rid' => '24t1q26gquirgqa78g',
          'start_time' => '2024-01-02 00:20:05',
          'end_time' => '2023-01-20 01:31:57',
          'col_x' => 100,
          'col_y' => 57,
          'type' => '12354'],
        ['rid' => '24t1q26gquirgqa78g',
          'start_time' => '2024-01-05 11:15:03',
          'end_time' => '2023-01-05 12:35:47',
          'col_x' => 123,
          'col_y' => 5678,
          'type' => '12354'],
        ['rid' => '24t1q26gquirgqa78g',
          'start_time' => '2024-01-05 19:58:01',
          'end_time' => '2023-01-05 20:00:05',
          'col_x' => 50,
          'col_y' => 450,
          'type' => '12354']        
      ];

      $total_array = [];

      // and not (日期A =  
      // (select MAX(日期A) from 表A where 流水號 = ?  and date_format(日期A, "%Y-%m-%d") = 2024-03-23) 
      // and 項目A + 項目B < 1000)
      foreach ($mock_DB_result as $data_row) {
        $start_time = $data_row->start_time;
        $start_day = Carbon::parse($start_time)->format('Y-m-d');
        $end_time = $data_row->end_time;
        $col_x = $data_row->col_x;
        $col_y = $data_row->col_y;
        $type = $data_row->type;
        $type_spilt_1 = substr($type, 0, 3);
        $type_spilt_2 = substr($type, 3 );

        
        $temp_array_key = $start_day.'_'.$type;
        if (!(isset($total_array[$temp_array_key]))) {
          $temp_array = [
            'start_date' => $start_day,
            'col_x+y' => $col_x + $col_y,
            'type_spilt_1' => $type_spilt_1,
            'type_spilt_2' => $type_spilt_2,
            // 其他要存入DB的欄位
          ];
  
          $total_array[$temp_array_key] = $temp_array;
        } else {
          $total_array[$temp_array_key]['col_x+y'] += $col_x + $col_y;;
        }
      }
      
      $total_array_actual = [
        '2024-01-01_12355' => ['start_date' => '2024-01-01', 'col_x+y' => 6900, 'type_spilt_1' => '123', 'type_spilt_2' => '55'],
        '2024-01-02_12355' => ['start_date' => '2024-01-02', 'col_x+y' => 6200, 'type_spilt_1' => '123', 'type_spilt_2' => '55'],
        '2024-01-02_12354' => ['start_date' => '2024-01-02', 'col_x+y' => 157, 'type_spilt_1' => '123', 'type_spilt_2' => '54'],
        '2024-01-05_12354' => ['start_date' => '2024-01-05', 'col_x+y' => 5801, 'type_spilt_1' => '123', 'type_spilt_2' => '54']
      ];

      // foreach insert total_array to DB
      foreach ($total_array as $key => $value) {
        // FakeModel::insert($value);
      }

      $this->assertEquals($total_array, $total_array_actual);
    }
}
