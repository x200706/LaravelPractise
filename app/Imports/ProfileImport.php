<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProfileImport implements ToCollection // 有篇感覺比較複雜的教學文章他是toModel嘛
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

    }
}