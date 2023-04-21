<?php
declare (strict_types = 1);

namespace app\teach\validate;

use think\Validate;

class Jiaoyanzu extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'category_id|分类'     =>  'require|number',
        'title|标题'     =>  'requireIf:category_id,12502|length:2,25',
        'ruxuenian|年级'     =>  'requireIf:category_id,12501|length:4',
        'subject_id|学科'     =>  'requireIf:category_id,12502|array',
        'beizhu|备注'    =>   'max:200',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'create'  =>  ['title', 'category_id', 'ruxuenian', 'subject_id', 'beizhu'],
        'edit'  =>  ['id', 'title', 'category_id', 'ruxuenian', 'subject_id', 'beizhu'],
    ];
}
