<?php

namespace app\teach\validate;

use think\Validate;

class Banji extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|ID'         =>     'require|number',
        'school_id|学校'     =>      'require|number',
        'ruxuenian|年级'  =>      'require|number',
        'bjsum|班级数'    =>      'require|number',
        'alias|别名' =>  'max:24',
        'banzhuren|别名' =>  'number'
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
            'school_id'
            ,'ruxuenian'
            ,'bjsum'
        ],
        'alias'  =>  [
            'id'
            ,'alias'
        ],
        'banzhuren'  =>  [
            'id'
            ,'banzhuren'
        ],
    ];
}
