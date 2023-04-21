<?php
declare (strict_types = 1);

namespace app\listener;

// 实例化考试状态事件
use \app\event\MyEvent;

class KaoshiTongJi
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
        if($ksInfo->luru !== 0)
        {
            return $this->error('请先在考试列表中禁止成绩编辑，再进行统计操作！', '/login/err');
        }
        if($ksInfo->status == 0)
        {
            return $this->error('本次考试已经结束，不能进行操作！', '/login/err');
        }

        return $ksInfo;
    }
}
