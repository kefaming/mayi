<?php
namespace app\chengji\controller;
// 引用控制器基类
use app\base\controller\AdminBase;
// 引用成绩统计数据模型
use app\chengji\model\TongjiSch as SCHTJ;

class Schtongji extends AdminBase
{

    // 统计全区年级成绩
    public function tongji()
    {
        // 获取变量
        $kaoshi_id = input('post.kaoshi_id');
        // 判断考试状态
        event('kstj', $kaoshi_id);
        // 统计成绩
        $schtj = new SCHTJ;
        $data = $schtj->tjSchool($kaoshi_id);

        if(true == $data)
        {
            $data=['msg'=>'完成','val'=>1];
            $src = [
                'kaoshi_id' => $kaoshi_id,
                'category_id' => 12005,
            ];
            event('tjlog', $src);
        }else{
            $data=['msg'=>'数据处理错误','val'=>0];
        }

        return json($data);
    }


    // 统计学生在班级名次
    public function schOrder()
    {
        // 获取变量
        $kaoshi_id = input('post.kaoshi_id');
        // 判断考试状态
        event('kstj', $kaoshi_id);

        // 统计成绩
        $schtj = new SCHTJ;
        $data = $schtj->schOrder($kaoshi_id);

        if(true == $data)
        {
            $data=['msg'=>'完成','val'=>1];
            $src = [
                'kaoshi_id' => $kaoshi_id,
                'category_id' => 12006,
            ];
            event('tjlog', $src);
        }else{
            $data=['msg'=>'数据处理错误','val'=>0];
        }

        return json($data);
    }


}
