<?php

namespace app\kaoshi\validate;

use think\Validate;

class Kaoshi extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|ID'         =>     'require:number',
        'title|标题'      =>  'require|length:1,25',
        'zuzhi_id|组织单位'      =>  'require|number',
        'xueqi_id|学期'      =>  'require|number',
        'category_id|分类'      =>  'require|number',
        'fanwei_id|查看范围'      =>  'number',
        'bfdate|考试时间'      =>  'require|date',
        'enddate|考试时间'      =>  'require|date',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create'  =>  [
            'title'
            ,'zuzhi_id'
            ,'xueqi_id'
            ,'category_id'
            ,'fanwei_id'
            ,'bfdate'
            ,'enddate'
        ],
        'edit'  =>  [
            'id'
            ,'title'
            ,'zuzhi_id'
            ,'xueqi_id'
            ,'category_id'
            ,'fanwei_id'
            ,'bfdate'
            ,'enddate'
        ],
    ];
}
