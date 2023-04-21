<?php
declare (strict_types = 1);

namespace app\tools\validate;

use think\Validate;

class File extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'file|文件格式必须为jpg,jpeg,png,xls,xlsx' => 'fileExt:jpg,png,jpeg,xls,xlsx'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [

    ];
}
