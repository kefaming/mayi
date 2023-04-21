<?php
declare (strict_types = 1);

namespace app\system\validate;

use think\Validate;

class ClassRoom extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|ID'     =>  'require|number',
        'title|教室名称'     =>  'require|length:2,15',
        'weizhi|位置'     =>  'length:2,100',
        'category_id|教室功能'      =>      'number',
        'shangke|是否可以上课 '     =>  'require|boolean',
        'beizhu|备注'    =>  'length:2,100'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create'  =>  [
            'title'
            ,'weizhi'
            ,'category_id'
            ,'shangke'
            ,'status'
            ,'beizhu'
        ],
        'edit'  =>  [
            'id'
            ,'title'
            ,'weizhi'
            ,'category_id'
            ,'shangke'
            ,'status'
            ,'beizhu'
        ],
    ];
}
