<?php
namespace App\Service;

use Carbon\Carbon; // 之後會用到

class DateConvertService {
  public function covertToDate($excelTimestamp) {
    // https://stackoverflow.com/questions/55390456/laravel-excel-maatwebsite-3-1-import-date-column-in-excel-cell-returns-as-unkno
    $unitTimestamp = ($excelTimestamp - 25569) * 86400;
    return gmdate("Y-m-d", $unitTimestamp);
  }
}