<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

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
    /* 這樣SQL是對的（ORM查也正常）!! 但是各種ORM寫法用到$grid->model下方就會爛掉<-原因出在$grid->model不知何故得好好select欄位，selectRaw('*')會爛掉...
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
     */

    // 不服來辯 SQL打印測試
    // $sql = ChiikawaProfile::selectRaw('
    //   chiikawa_profile.id,
    //   chiikawa_profile.created_at,
    //   chiikawa_profile.name,
    //   chiikawa_profile.birthday,
    //   chiikawa_profile.sign,
    //   sign_lucky_color.lucky_color,
    //   sign_lucky_jewelry.lucky_jewelry')
    //     ->leftJoin('sign_lucky_color', 'chiikawa_profile.sign', '=', 'sign_lucky_color.sign')
    //     ->leftJoin('sign_lucky_jewelry', 'chiikawa_profile.sign', '=', 'sign_lucky_jewelry.sign')->toSql();
    // Log::info($sql);

    // $suspected_wrong_sql = ChiikawaProfile::selectRaw('*')
    //     ->leftJoin('sign_lucky_color', 'chiikawa_profile.sign', '=', 'sign_lucky_color.sign')
    //     ->leftJoin('sign_lucky_jewelry', 'chiikawa_profile.sign', '=', 'sign_lucky_jewelry.sign')->toSql();
    // Log::info($suspected_wrong_sql);

    $grid = new Grid(new ChiikawaProfile());
    // 下面這樣寫資料會亂掉（不分原生/ORM/grid model）
    // 先說下情境好了 寶石表跟顏色表都有個同名欄位，只會在一張表出現，現在要讓這個合併的欄位顯示進grid（更：其實用left join理論上應該要能可以，可是grid又再搞，就想說試試看union....）
    // $another_result = ChiikawaProfile::selectRaw('
    //   chiikawa_profile.id,
    //   chiikawa_profile.created_at,
    //   chiikawa_profile.name,
    //   chiikawa_profile.birthday,
    //   chiikawa_profile.sign,
    //   sign_lucky_color.lucky_color,
    //   sign_lucky_color.same_name_col as same_name_col')
    //     ->leftJoin('sign_lucky_color', 'chiikawa_profile.sign', '=', 'sign_lucky_color.sign');
    
    // $grid->model()->selectRaw('
    // DISTINCT chiikawa_profile.id,
    // chiikawa_profile.created_at,
    // chiikawa_profile.name,
    // chiikawa_profile.birthday,
    // chiikawa_profile.sign,
    // sign_lucky_jewelry.lucky_jewelry,
    // sign_lucky_jewelry.same_name_col as same_name_col')
    //   ->leftJoin('sign_lucky_jewelry', 'chiikawa_profile.sign', '=', 'sign_lucky_jewelry.sign')
    //   ->union($another_result);
    // 以上區段查詢結果異常原因是UNION的特性 https://www.cnblogs.com/buwuliao/p/11121352.html
    
    // 補充去重：unique是用來處理collection的..；真要用可能試試->distinct()

    // 另外這樣也會壞掉......
    // $grid->model()->selectRaw('
    // chiikawa_profile.id,
    // chiikawa_profile.created_at,
    // chiikawa_profile.name,
    // chiikawa_profile.birthday,
    // chiikawa_profile.sign,
    // sign_lucky_jewelry.lucky_jewelry,
    // sign_lucky_jewelry.same_name_col,
    // sign_lucky_color.lucky_color,
    // sign_lucky_color.same_name_col')
    //   ->leftJoin('sign_lucky_jewelry', 'chiikawa_profile.sign', '=', 'sign_lucky_jewelry.sign')
    //   ->leftJoin('sign_lucky_color', 'chiikawa_profile.sign', '=', 'sign_lucky_color.sign');
    // 可是SQL可以這樣寫，我!~!%#^~!#@
    /*
    select 
    chiikawa_profile.id,
    chiikawa_profile.created_at,
    chiikawa_profile.name,
    chiikawa_profile.birthday,
    chiikawa_profile.sign,
    sign_lucky_jewelry.lucky_jewelry,
    sign_lucky_jewelry.same_name_col,
    sign_lucky_color.lucky_color,
    sign_lucky_color.same_name_col
    from chiikawa_profile 
    left join sign_lucky_color on chiikawa_profile.sign = sign_lucky_color.sign
    left join sign_lucky_jewelry on chiikawa_profile.sign = sign_lucky_jewelry.sign
    order by chiikawa_profile.id asc -- 出來結果可正常咪怒
    */
    // 而且ORM語法查出來也是對的 就grid model的都會壞掉 暈
    // $suspected_strange_result = ChiikawaProfile::selectRaw('
    //   chiikawa_profile.id,
    //   chiikawa_profile.created_at,
    //   chiikawa_profile.name,
    //   chiikawa_profile.birthday,
    //   chiikawa_profile.sign,
    //   sign_lucky_jewelry.lucky_jewelry,
    //   sign_lucky_jewelry.same_name_col,
    //   sign_lucky_color.lucky_color,
    //   sign_lucky_color.same_name_col')
    //     ->leftJoin('sign_lucky_jewelry', 'chiikawa_profile.sign', '=', 'sign_lucky_jewelry.sign')
    //     ->leftJoin('sign_lucky_color', 'chiikawa_profile.sign', '=', 'sign_lucky_color.sign')->orderBy('created_at','asc')->get();
    // $grid->setTable($suspected_strange_result); // 可能會有問題？
    // Log::info($suspected_strange_result);
    
    // 備註 $grid->setData是直接塞入指定欄位
    // setTable裡面如果傳入查詢結果會加太多""然後拋錯（待還原）
    // 這麼麻煩...有朋友建議我弄個視圖幫它建個Model接過來算了
    // https://github.com/z-song/laravel-admin/issues/4998 網友說你直接下raw sqlㄅ
    // 夾縫中求生存Orz|||
    
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
    // $grid->column('lucky_color', '2024星座幸運色');
    // $grid->column('lucky_jewelry', '2024星座幸運寶石');
    // $grid->column('same_name_col', '同名但只會有其中一張表有的欄位');
    // 解決leftjoin顯示不理想問題：
    // 以上關聯欄位在Model那邊設置一對一方法，
    // 然後在這邊用display寫匿名方法，裡面的$this就會是當前Model，去呼那個方法return那個值 
    // 如果要兩張表取有資料那張，可以寫?? null判斷式
    // 在其他地方寫成功過了－至少這樣取值跟顯示是不會出錯-.-

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
