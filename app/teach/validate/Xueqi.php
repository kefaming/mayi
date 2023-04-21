<?php

namespace app\teach\validate;

use think\Validate;

class Xueqi extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|ID'         =>     'require:number',
        'title|标题'     =>  'require|length:2,25',
        'xuenian|学年'     =>  'require|length:1,15',
        'category_id|分类'     =>  'require|number',
        'bfdate|时间'     =>  'require|date',
        'enddate|时间'     =>  'require|date',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create'  =>  ['title', 'xuenian', 'category_id', 'bfdate', 'enddate'],
        'edit'  =>  ['id', 'title', 'xuenian', 'category_id', 'bfdate', 'enddate'],
    ];
}
