<?php

namespace app\kaohao\validate;

use think\Validate;

class Kaohao extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'kaoshi_id|考试号'      =>  'require|number',
        'school_id|学校'      =>  'require|number',
        'ruxuenian|入学年'      =>  'require|number',
        'nianji|年级名称'      =>  'require|chs',
        'banji_id|参考班级'      =>  'require',
        'paixu|班级排序'      =>  'require|number',
        'student_id|学生'      =>  'require',
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
            'kaoshi_id'
            ,'school_id'
            ,'ruxuenian'
            ,'nianji'
            ,'banji_id'
            ,'paixu'
            ,'student_id'
        ],
        'createAll'  =>  [
            'kaoshi_id'
            ,'school_id'
            ,'banji_id'
        ],
        'edit'  =>  [
            'id'
            ,'school_id'
            ,'ruxuenian'
            ,'nianji'
            ,'banji_id'
            ,'paixu'
        ]
    ];
}
