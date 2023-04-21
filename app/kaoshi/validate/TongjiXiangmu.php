<?php
declare (strict_types = 1);

namespace app\kaoshi\validate;

use think\Validate;

class TongjiXiangmu extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|ID'      =>  'require|integer',
        'title|标题'      =>  'require|length:1,25',
        'biaoshi|标识'      =>  'require|length:1,25',
        'tongji|参与统计'      =>  'require|boolean',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $scene = [
        'create'  =>  [
            'title'
            ,'biaoshi'
            ,'tongji'
        ]
        ,'edit'   =>  [
            'id'
            ,'title'
            ,'biaoshi'
            ,'tongji'
        ]
    ];
}
