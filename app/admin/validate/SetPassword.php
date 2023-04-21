<?php

namespace app\admin\validate;

use think\Validate;

class SetPassword extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'oldpassword|原密码' => 'require',
        'newpassword|新密码' => ['require', 'regex' => '/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){6,20}$/'],
        'newpassword2|第二次密码' => 'require|confirm:newpassword',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'newpassword.regex' => '只能输入7-20个字母、数字、半角句号、下划线',
    ];
}
