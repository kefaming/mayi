<?php

namespace app\kaoshi\validate;

use think\Validate;

class Biaoqian extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'banji_id|班级'      =>  'require|array',
        'kaoshi_id|考试'      =>  'require|number',
        'subject_id|学科'      =>  'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'biaoqian'  =>  [
            'banji_id'
            ,'kaoshi_id'
            ,'subject_id'
        ],
        'caiji'  =>  [
            'banji_id'
            ,'kaoshi_id'
            ,'subject_id'
        ]
    ];
}
