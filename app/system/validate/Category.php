<?php

namespace app\system\validate;

use think\Validate;

class Category extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|ID'         =>     'require:number',
        'title|类别名称' =>  'require|chs|length:2,50',
        'paixu|排序'  =>  'number|max:999',
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
            ,'paixu'
        ],
        'edit'  =>  [
            'id'
            ,'title'
            ,'paixu'
        ],
    ];
}
