<?php

namespace App\Admin\Actions\Chiikawa;
use Encore\Admin\Grid\Exporters\ExcelExporter; 

class ExportProfile extends ExcelExporter // 繼承自動欄寬還可以美化Excel喔
{
  protected $fileName = '匯出小可愛個人資料.xlsx';

  protected $columns = [
      'id'      => 'id',
      'name'   => '姓名',
      'sign' => '星座',
      'birthday' => '生日',
  ];  
  // 如果要輸出join表格，return值要模仿Controller提到的原生ORM關聯取值方法
}