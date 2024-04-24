<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;

use App\Admin\Actions\Chiikawa\ImportProfile;
use App\Admin\Actions\Chiikawa\ExportProfile;;
use App\Models\ChiikawaProfile;

class MyController extends AdminController
{ // 修改不用重新run欸

 /**
  * Title for current resource.
  *
  * @var string
  */
  protected $title = '我的測試頁面';

 /**
  * Make a grid builder.
  *
  * @return Grid
  */
  protected function grid(){
    $grid = new Grid(new ChiikawaProfile());
    
    $grid->disableCreateButton(); // 禁用新增按鈕
    $grid->disableActions(); // 禁用單行異動按鈕
    // $grid->disableFilter(); // 禁用漏斗
    // $grid->disableExport(); // 禁用匯出
    $grid->disableRowSelector(); // 禁用選取
    $grid->disableColumnSelector(); // 禁用像格子圖案的按鈕
    
    $grid->tools(function (Grid\Tools $tools) {
       $tools->append(new ImportProfile());
    });
    $grid->filter(function (Grid\Filter $filter) {
      $filter->expand();
    });

    $grid->column('id', 'id');
    $grid->column('name', '姓名');
    $grid->column('birthday', '生日')->sortable();;
    $grid->column('sign', '星座');

    $grid->exporter(new ExportProfile);
    
    return $grid;
  }

 // 如果路由沒有use AdminController 就得自己寫個index進入 @@
 // public function index(Content $content) {
 //   return $content
 //     ->title($this->title)
 //     ->body($this->grid());
 // }
}
