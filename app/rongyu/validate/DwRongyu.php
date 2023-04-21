<?php

namespace app\rongyu\validate;

use think\Validate;

class DwRongyu extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'url|请先上传荣誉图片'     =>  'require',
        'id|ID'     =>  'require',
        'title|荣誉标题'     =>  'length:1,100',
        'category_id|荣誉类型'     =>  'require|number',
        'hjschool_id|获奖单位'     =>  'require|number',
        'fzshijian|发证时间'     =>  'require|date',
        'fzschool_id|发证单位'     =>  'require|number',
        'jiangxiang_id|奖项'     =>  'require|number',
        'teacher_id|参与人'      =>'require',
        'project|项目'=>'length:1,100'
    ];


    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    // 验证场景
    protected $scene = [
        'create'  =>  ['project','url', 'title', 'category_id', 'hjschool_id', 'fzshijian', 'fzschool_id', 'jiangxiang_id'],
        'edit'  =>  ['project','id', 'title', 'category_id', 'hjschool_id', 'fzshijian', 'fzschool_id', 'jiangxiang_id'],
        'createall'  =>  ['title', 'url'],
    ];
}
