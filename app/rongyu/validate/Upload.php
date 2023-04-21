<?php
declare (strict_types = 1);

namespace app\rongyu\validate;

use think\Validate;

class Upload extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'text' => 'require',
        'serurl' => 'require',
        'isSave' => 'require|boolean'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [

    ];

    // 验证场景
    protected $scene = [
        'save'  =>  ['text', 'serurl', 'isSave'],
        'nosave'  =>  ['text', 'serurl']
    ];
}
