<?php

namespace App\Admin\Actions\Chiikawa;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProfileImport;

use App\Models\ChiikawaProfile;
use Illuminate\Support\Facades\Log;

use App\Service\DateConvertService;

class ImportProfile extends Action
{
    protected $selector = '.import-profile';

    // DI注入Service
    // protected $dateConvertService;
    // public function __construct(
    //   DateConvertService $dateConvertService
    // ){
    //     $this->dateConvertService = $dateConvertService;
    // } // 這邊不是建構子注入不能用，是你失去了空參數建構子，那別處如果不是注入而是調用時得傳個東西進來吧->但這邊似乎還真的不能用 會讓工具失效............

    public function handle(Request $request)
    {
      $excel = $request->file('file');
      // 存入DB
      $sheet = Excel::toArray(new ProfileImport(), $excel);
      $sheet = $sheet[0];
      array_shift($sheet); // 去除表頭

      // forEach
      foreach ($sheet as $row) {
        $name = $row[0];
        $dateConvertService = new DateConvertService();
        $birthday = $dateConvertService->covertToDate($row[1]);
        
        // 可以這樣用嗎..？不行 會說non static method
        // $birthday = DateConvertService::covertToDate($row[1]);
        
        // 如果用建構子注入..
        // $birthday = $this->dateConvertService->covertToDate($row[1]); // 注意!!調用注入的物件可不要加$號啊
        $sign = $row[2];
        
        // 檢查是否合法（略）

        // 匯入DB
        $data = array(
          'name' => $name,
          'birthday' => $birthday,
          'sign' => $sign,
        );
        ChiikawaProfile::insert($data);
      }
 
      return $this->response()->success('匯入小可愛資料完成！！')->refresh();
    }
  
    public function form()
    {
        $this->file('file', '請上傳xlsx檔案');
    }


    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-profile">匯入小可愛個人資料</a>
        HTML;
    }
}