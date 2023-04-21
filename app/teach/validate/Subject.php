<?php

namespace app\teach\validate;

use think\Validate;

class Subject extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|ID'         =>     'require:number',
        'title|名称'     =>  'require|length:1,25',
        'jiancheng|简称'     =>  'require|length:1,6',
        'lieming|列标识'     =>  'require',
        'category_id|分类'     =>  'require|number',
        'paixu|排序'     =>  'number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create'  =>  ['title', 'jiancheng', 'lieming', 'category_id', 'paixu'],
        'edit'  =>  ['id', 'title', 'jiancheng', 'lieming','category_id', 'paixu'],
    ];
}
