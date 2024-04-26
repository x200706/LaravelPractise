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
    /* 這樣SQL是對的!! 但是各種ORM寫法用到$grid->model就會爛掉<-20240426原因出在沒有好好select欄位，selectRaw('*')會爛掉
     select 
     chiikawa_profile.id,
     chiikawa_profile.created_at,
     chiikawa_profile.name,
     chiikawa_profile.birthday,
     chiikawa_profile.sign,
     sign_lucky_color.lucky_color,
     sign_lucky_jewelry.lucky_jewelry
     from chiikawa_profile 
     left join sign_lucky_color on chiikawa_profile.sign = sign_lucky_color.sign
     left join sign_lucky_jewelry on chiikawa_profile.sign = sign_lucky_jewelry.sign
     order by chiikawa_profile.id asc

     另外result的merge是關於collection；還有union應該也是能用的，只是沒有好好select就容易爛掉
     */
    
    $grid = new Grid(new ChiikawaProfile());
    $grid->model()->selectRaw('
    chiikawa_profile.id,
    chiikawa_profile.created_at,
    chiikawa_profile.name,
    chiikawa_profile.birthday,
    chiikawa_profile.sign,
    sign_lucky_color.lucky_color,
    sign_lucky_jewelry.lucky_jewelry')
      ->leftJoin('sign_lucky_color', 'chiikawa_profile.sign', '=', 'sign_lucky_color.sign')
      ->leftJoin('sign_lucky_jewelry', 'chiikawa_profile.sign', '=', 'sign_lucky_jewelry.sign');

    // 備註 $grid->setData是直接塞入指定欄位
    // setTable裡面如果傳入查詢結果會加太多""然後拋錯（待還原）
    
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
      $filter->disableIdFilter();
      $filter->between('birthday', '日期')->datetime();
    });

    // 展開有新版寫法<-Uhmm不過大部分都會做自己的搜尋器吧？！還是會需要上面那個函數啊
    // $grid->expandFilter();

    $grid->column('id', 'id');
    $grid->column('name', '姓名');
    $grid->column('birthday', '生日')->sortable();;
    $grid->column('sign', '星座');
    $grid->column('lucky_color', '2024星座幸運色');
    $grid->column('lucky_jewelry', '2024星座幸運寶石');

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
