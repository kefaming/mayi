<?php

namespace app\chengji\validate;

use think\Validate;

class Cjdownload extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'banji_id|班级'      =>  'require',
        'kaoshi_id|考试'      =>  'require|number',
        'subject_id|学科'     =>  'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'download'  =>  [
            'kaoshi_id'
            ,'banji_id'
        ]
        ,'deletemore' => [
            'banjiid'
            ,'kaoshi_id'
            ,'subject_id'
        ]
    ];
}
