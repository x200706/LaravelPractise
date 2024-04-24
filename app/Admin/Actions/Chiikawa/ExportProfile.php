<?php

namespace App\Admin\Actions\Chiikawa;
use Encore\Admin\Grid\Exporters\ExcelExporter; 

class ExportProfile extends ExcelExporter
{
  protected $fileName = '匯出小可愛個人資料.xlsx';

  protected $columns = [
      'id'      => 'id',
      'name'   => '姓名',
      'sign' => '星座',
      'birthday' => '生日',
  ];  
}