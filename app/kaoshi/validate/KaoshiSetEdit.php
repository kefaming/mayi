<?php
declare (strict_types = 1);

namespace app\kaoshi\validate;

use think\Validate;

class KaoshiSetEdit extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var
     */
    protected $rule = [
        'id|ID'      =>  'require|integer',
        'kaoshi_id|考试ID'      =>  'require|integer',
        'manfen|满分'      =>  'require|number',
        'youxiu|优秀分数线'      =>  'require|number',
        'lianghao|良好分数线'      =>  'require|number',
        'jige|及格分数线'      =>  'require|number',
        'youxiu|优秀人数比'      =>  'require|number',
        'lianghao|良好人数比'      =>  'require|number',
        'jige|及格人数比'      =>  'require|number',
        // 'lieming|列名'      =>  'require|array',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'edit'  =>  ['id','kaoshi_id','manfen','youxiu','lianghao','jige','youxiubi','lianghaobi','jigebi'],
    ];
}
