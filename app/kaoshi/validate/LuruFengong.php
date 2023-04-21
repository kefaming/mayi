<?php
declare (strict_types = 1);

namespace app\kaoshi\validate;

use think\Validate;

class LuruFengong extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'kaoshi_id|考试'      =>  'require|length:1,25',
        'admin_id|教师'      =>  'require',
        'banji_id|班级'      =>  'require|array',
        'subject_id|学科'      =>  'require|array',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create'  =>  [
            'kaoshi_id'
            ,'admin_id'
            ,'subject_id'
            ,'banji_id'
        ],
    ];
}
