<?php
/*
 * @Author: 大连巴掌
 * @Date: 2022-05-25 12:30:59
 * @LastEditors: 大连巴掌
 * @LastEditTime: 2022-06-17 09:03:55
 * @Description: 
 * @FilePath: \x.cj.cn\app\teach\validate\FenGong.php
 */
declare (strict_types = 1);

namespace app\teach\validate;

use think\Validate;

class FenGong extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|ID'         =>     'require|number',
        'teacher_id|教师'     =>      'require|number',
        'subject_id|学科'  =>      'require|array',
        'banji_id|班级'    =>      'require|array',
        'xueqi_id|学期'    =>      'require|number',
        'yxueqi_id|源学期'    =>      'require|number',
        'bfdate|上任时间' =>  'require|date',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $scene = [
        'create'  =>  [
            'teacher_id'
            ,'subject_id'
            ,'banji_id'
            ,'xueqi_id'
            ,'bfdate'
        ],
        'copy'  =>  [
            'yxueqi_id'
            ,'xueqi_id'
        ]
    ];
}
