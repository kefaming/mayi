<?php
declare (strict_types = 1);

namespace app\teach\validate;

use think\Validate;

class JiaoyanZuzhang extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|ID' => 'require',
        'teacher_id|教师'     =>  'require|number',
        'jiaoyanzu_id|教研组'     =>  'require|number',
        'bfdate|开始时间'     =>  'require|date',
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
            'teacher_id'
            ,'jiaoyanzu_id'
            ,'bfdate'
        ],
        'edit'  =>  [
            'id'
            ,'teacher_id'
            ,'bfdate'
        ],
    ];
}
