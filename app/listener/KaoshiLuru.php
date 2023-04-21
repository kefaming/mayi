<?php
declare (strict_types = 1);

namespace app\listener;

// 实例化考试状态事件
use \app\event\MyEvent;

class KaoshiLuru
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */

    use \liliuwei\think\Jump;

    public function handle($kaoshi_id)
    {
        $event = new MyEvent;
        $ksInfo = $event->ksInfo($kaoshi_id);
        if($ksInfo){
           if($ksInfo->status == 0)
            {
                return $this->error('本次考试已经结束！','/login/err');
            } 
           if($ksInfo->luru == 0)
            {
                return $this->error('本次考试成绩已经不允许编辑！','/login/err');
            } 
        }else{
            return $this->error('没有找到考试。','/login/err');
        }

        return $ksInfo;
    }
}
