<?php
declare (strict_types = 1);

namespace app\chengji\validate;

use think\Validate;

class RenKe extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|班级成绩编号' => 'require|array',
        'baji_id|班级编号'      =>  'require|array',
        'teacher_id|教师'      =>  'require',
        'subject_id|学科' => 'require',
        'kaoshi_id|考试编辑' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'edit'  =>  [
            'id'
            ,'teacher_id'
        ],
        'editteacher'  =>  [
            'banji_id'
            ,'teacher_id'
            ,'subject_id'
            ,'kaoshi_id'
        ]
    ];
}
