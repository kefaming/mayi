<?php
declare (strict_types = 1);

namespace app\teach\validate;

use think\Validate;

class BanZhuRen extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'id|ID'     =>      'require|number',
        'teacher_id|教师'     =>      'require|number',
        'banji_id|班级'     =>      'require|number',
        'bfdate|接任时间'     =>      'require|date',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    protected $scene = [
        'create'  =>  [
            'banji_id'
            ,'teacher_id'
            ,'bfdate'
        ],
        'edit'  =>  [
            'id'
            ,'teacher_id'
            ,'bfdate'
        ],
    ];
}
