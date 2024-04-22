<?php

namespace App\Admin\Actions\Chiikawa;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

use App\Imports\ProfileImport;
use App\Models\ChiikawaProfile;

class ImportProfile extends Action
{
    protected $selector = '.import-profile';

    public function handle(Request $request)
    {
        $excel = $request->file('file');
        // 存入DB
        $sheet  = Excel::toArray(new ProfileImport(), $excel);
        // 檢查是否合法
        // forEach

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