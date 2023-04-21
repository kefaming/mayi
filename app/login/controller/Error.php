<?php

namespace app\login\controller;

// 引用控制器类
use think\Controller;

class Error extends Controller
{
    public function index()
    {
        // 跳转页面
        $this->error('不好意思，您访问的页面不存在~');
    }

     public function _empty()
    {
        $this->error('不好意思，您访问的页面不存在~');
    }
}
