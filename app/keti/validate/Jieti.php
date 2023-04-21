<?php
declare (strict_types = 1);

namespace app\keti\validate;

use think\Validate;

class Jieti extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|ID'         =>     'require:number',
        'title|课题册标题'     =>  'require|length:1,100',
        'shijian|结题时间'     =>  'require|date',
        'danwei_id|结题单位'     =>  'require|number',
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
            ,'shijian'
            ,'danwei_id'
        ],
        'edit'  =>  [
            'id'
            ,'title'
            ,'shijian'
            ,'danwei_id'
        ],
    ];
}
