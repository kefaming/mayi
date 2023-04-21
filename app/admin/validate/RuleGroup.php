<?php

namespace app\admin\validate;

use think\Validate;

class RuleGroup extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title|规则名' =>  'require|chs|length:2,100',
        'rules|规则'   =>  'require|length:1,10000',
        'miaoshu|排序'  =>  'require|length:2,180',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
}
