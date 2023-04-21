<?php

namespace app\system\validate;

use think\Validate;

class SystemBase extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|ID'     =>  'require|integer',
        'keywords|关键字'      =>  'require|length:2,60',
        'description|描述'      =>  'require|length:1,100',
        'thinks|感谢'      =>  'require|length:1,80',
        'danwei|单位'      =>  'require|length:2,80',
        'xuenian|学年节点' =>   'require|date',
        'gradelist|年级最大数' => 'require|length:1,200',
        'classmax|年级最大数' => 'require|integer',
        'classalias|别名' => 'require|boolean',
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
            ,'keywords'
            ,'description'
            ,'thinks'
            ,'danwei'
            ,'xuenian'
            ,'gradelist'
            ,'classmax'
            ,'classalias'
        ],
    ];
}
