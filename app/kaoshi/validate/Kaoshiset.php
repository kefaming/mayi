<?php

namespace app\kaoshi\validate;

use think\Validate;

class Kaoshiset extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var
     */
	protected $rule = [
        'id|ID'         =>     'require:number',
        'kaoshi_id|考试ID'      =>  'require|integer',
        'ruxuenian|年级'      =>  'require|integer',
        'nianjiname|年级名称'      =>  'require|chsAlphaNum',
        'subject_id|学科'      =>  'require|array',
        'manfen|满分'      =>  'require|array',
        'youxiu|优秀分数线'      =>  'require|array',
        'lianghao|良好分数线'      =>  'require|array',
        'jige|及格分数线'      =>  'require|array',
        'youxiubi|优秀人数比'      =>  'require|array',
        'lianghaobi|良好人数比'      =>  'require|array',
        'jigebi|及格人数比'      =>  'require|array',
        // 'lieming|列名'      =>  'require|array',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create'  =>  ['kaoshi_id', 'nianji','nianjiname', 'subject_id', 'manfen', 'youxiu', 'lianghao', 'jige', 'youxiubi', 'lianghaobi', 'jigebi'],
    ];
}
