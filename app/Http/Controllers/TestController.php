<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
  // 還有單行為控制器 但似乎不強制
  public function read($id)
    {
      // $this->middleware('auth');
      return $id;
    }

  public function ro()
    {
      $url = route('nt', ['n'=>10]);
      return $url;
    }

  public function autoJson()
    {
      return [1 ,2 ,3];
      // response()->header可以加料
    }
  // 重定向略
}
