<?php
declare (strict_types = 1);

namespace app\tools\controller;

class OnLine
{
    use \liliuwei\think\Jump;
    // 将关联学生成绩转换成以学科列名为key得分为value的数组
    public function jump($url = '/login/err', $msg = '')
    {
        $ser = request()->domain();
        if($msg == "")
        {
            $msg = "页面或者数据被<a href=''> 纸飞机 </a>运到火星了，啥都看不到了…";
        }

        $isajax = request()->isAjax();
        if($isajax)
        {
            $this->error($msg, $url);
        }else{
            echo "<script language='javascript' type='text/javascript'>top.location.href='".$url."'</script>";
        }
        return true;
    }

    // 获取用户信息
    public function myInfo()
    {
        $ad = new \app\admin\model\Admin;
        $adInfo = $ad->searchOne(session('user_id'));

        return $adInfo;
    }
}
