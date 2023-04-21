<?php

namespace app\keti\validate;

use think\Validate;

class KetiInfo extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|请先上传立项通知书图片'      =>    'require',
        'lixiang_id|课题册ID'      =>    'require',
        'jieti_id|课题册ID'      =>    'require',
        'title|课题名称'     =>  'require|length:1,100',
        'bianhao|课题编号'     =>  'require|length:1,50',
        'teacher_id|课题主持人'     =>  'require',
        'fzdanwei_id|课题负责单位'     =>  'require|number',
        'subject_id|课题所属学科'     =>  'require|number',
        'category_id|课题研究类型'     =>  'require|number',
        'jhjtshijian|计划结题时间'     =>  'require|date',
        'cyteachers|参与人'    =>  'array',
        'jtpic|结题鉴定图片'    =>  'length:1,100',
        'jddengji_id|鉴定等级'    =>  'require|number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    // 验证场景
    protected $scene = [
        'create'  =>  ['ketice_id','title','bianhao','fzdanwei_id','subject_id','category','jhjtshijian','teacher_id'],
        'edit'  =>  ['id','title','teacher_id','hjschool_id','subject_id','hjshijian','jiangxiang'],
        'addjieti'  =>  ['id','jieti_id','teacher_id','jtpic','jddengji_id'], # 在结题册中创建结题信息
        'editjieti'  =>  ['id','teacher_id','jtpic','jddengji_id'],  # 在课题列表中编辑结题信息，不需要更改结题册
    ];
}
