<?php

namespace app\keti\validate;

use think\Validate;

class Keti extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|ID'         =>     'require:number',
        'title|课题册标题'     =>  'require|length:1,100',
        'category_id|课题类型'     =>  'require|number',
        'lxshijian|立项时间'     =>  'require|date',
        'lxdanwei_id|立项单位'     =>  'require|number',
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
            'title'
            ,'category_id'
            ,'lxshijian'
            ,'lxdanwei_id'
        ],
        'edit'  =>  [
            'id'
            ,'title'
            ,'category_id'
            ,'lxshijian'
            ,'lxdanwei_id'
        ],
    ];
}
