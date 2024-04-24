// <?php

// namespace App\Exports;

// use Illuminate\Support\Collection;

// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;

// class ProfileExport implements FromCollection, WithHeadings {
//     protected $data;


//     public function __construct($data)
//     {
//         $this->data = $data;
//     }


//    public function collection()
//     {
//         return new Collection($this->data);
//     }

//   public function headings(): array
//   {
//       return [
//           'id',
//           '姓名',
//           '生日',
//           '星座',
//       ];
//   }
// }
// 這是傳給Excel類下面的靜態方法才要寫啦~~例如Excel::download(new XxxExport)
// Laravel Admin Grid原生支持匯出功能
// 直接看這篇：https://laravel-admin.org/docs/zh/1.x/model-grid-export
// 建立一個繼承ExcelExporter的物件，依照個人喜好調整後傳入方法$grid->exporter()即可