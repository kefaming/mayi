<?php
declare (strict_types = 1);

namespace app\listener;

// 实例化考试状态事件
use \app\event\MyEvent;

class KaoshiEdit
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
        $user_id = session('user_id');
        if ($user_id != 1 && $user_id != 2) {
        	if($ksInfo){
	           if($ksInfo->user_id != $user_id)
	            {
	                return $this->error('考试创建人才可以编辑考试信息！','/login/err');
	            } 
	        }else{
	            return $this->error('没有找到考试。','/login/err');
	        }
        }
        

        return $ksInfo;
    }
}
