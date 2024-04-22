<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiikawaProfile extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'chiikawa_profile';
    protected $primaryKey = 'id';

    public $incrementing = true; // 自增嗎？
    public $timestamps = true; // 要自動時間戳嗎？

    const CREATED_AT = 'created_at'; // 自增建立日期欄位
    const UPDATED_AT = null; // 自增異動日期欄位

}