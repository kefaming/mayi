<?php

namespace app\login\validate;

use think\Validate;

class Yanzheng extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'username|用户名'  =>  'require',
        'password|密码'  =>  'require',
        'category|用户身份'  =>  'require',
    ];

    protected $message = [
        'admin'  =>  [
            'username'
            ,'password'
            ,'category'
        ]
    ];
}
