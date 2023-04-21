<?php
declare (strict_types = 1);

namespace app\listener;

// 实例化考试状态事件
use \app\event\MyEvent;

class LuruFengong
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($srcfrom)
    {
        $event = new MyEvent;
        $banji = $event->luruFengong($srcfrom);
        return $banji;
    }
}
