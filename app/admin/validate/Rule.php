<?php

namespace app\admin\validate;

use think\Validate;

class Rule extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'title|规则名' =>  'require|chs|length:2,80',
        'name|规则'   =>  ['require', 'length:1,80', 'uset'=>'/^[a-zA-Z\/]*$/'],
        'paixu|排序'  =>  'number|max:999',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];
}
