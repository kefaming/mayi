<?php
declare (strict_types = 1);

namespace app\event;

use \app\kaoshi\model\Kaoshi;
use \app\kaoshi\model\TongjiLog;
use \app\Chengji\model\Chengji;

class MyEvent
{

    // 获取考试状态
    public function ksInfo($kaoshi_id = 0)
    {
        $kaoshi = new Kaoshi;
        $ksinfo = $kaoshi->kaoshiInfo($kaoshi_id);
        return $ksinfo;
    }


    // 添加考试统计日志
    public function addTongjiLog($kaoshi_id, $category_id)
    {
        // 添加统计日志
        $tjLog = new TongjiLog;
        $log = $tjLog::withTrashed()
                ->where('kaoshi_id', $kaoshi_id)
                ->where('category_id', $category_id)
                ->find();

        if(true == $log)
        {
            if($log->delete_time > 0)
            {
                $log->restore();
            }
            $log->update_time = time();
            $log->user_id = session('user_id');
            $data = $log->save();
        }else{
            $data = $tjLog->save([
                'kaoshi_id' => $kaoshi_id,
                'category_id' => $category_id,
                'user_id' => session('user_id'),
            ]);
        }

        $data ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        return $data;
    }


    // 获取用户班级权限
    public function userInfo($srcfrom)
    {
        $ad = new \app\admin\model\Admin;
        $adinfo = $ad->myQuanxian($srcfrom);
        return $adinfo;
    }


    // 获取本次考试录入分工
    public function luruFengong($srcfrom)
    {
        $fg = new \app\kaoshi\model\LuruFengong;
        $fginfo = $fg->auth($srcfrom);
        return $fginfo;
    }


    // 获取任务分工
    public function fenGong($srcfrom)
    {
        $fg = new \app\teach\model\FenGong;
        $fginfo = $fg->teacherFengong($srcfrom);
        return $fginfo;
    }

}
