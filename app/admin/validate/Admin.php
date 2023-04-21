<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
	protected $rule = [
        'id|用户编号'  =>  'require:number',
        'xingming|用户姓名'  =>  'require|chs|length:2,6',
        'quanpin|姓名全拼'     =>  'require|alpha|length:1,30',
        'shoupin|姓名首拼'     =>  'require|alpha|length:1,5',
        'username|帐号'  =>  'require|alphaDash|length:6,12|unique:admin',
        'group_id|角色'   =>  'require|array',
        'shengri|出生日期'   =>  'date',
        'sex|性别'   =>  'require|in:0,1,2',
        'phone|手机号'     =>  'require|mobile|unique:admin',
        'beizhu|备注'    =>  'max:80',
        'zhiwu_id|职务'      =>      'number',
        'zhicheng_id|职称'     =>  'require|number',
        'xueli_id|学历'    =>  'require|number',
        'biye|毕业院校'    =>  'length:1,50',
        'zhuanye|专业'     =>  'length:1,20',
        'worktime|参加工作时间'     =>  'date',
        'tuixiu|退休'        =>      'require|number|max:2',
        'beizhu|备注'        =>      'max:80',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'admincreate'  =>  [
             'xingming'
             ,'username'
             ,'shoupin'
             ,'quanpin'
             ,'group_id'
             ,'shengri'
             ,'sex'
             ,'phone'
             ,'beizhu'
             ,'zhiwu_id'
             ,'zhicheng_id'
             ,'xueli_id'
             ,'biye'
             ,'zhuanye'
             ,'worktime'
             ,'tuixiu'
        ],
        'adminedit'  =>  [
            'id'
             ,'xingming'
             ,'username'
             ,'shoupin'
             ,'quanpin'
             ,'group_id'
             ,'shengri'
             ,'sex'
             ,'phone'
             ,'beizhu'
             ,'zhiwu_id'
             ,'zhicheng_id'
             ,'xueli_id'
             ,'biye'
             ,'zhuanye'
             ,'worktime'
             ,'tuixiu'
        ],
        'infoedit'  =>  [
            'id'
             ,'xingming'
             ,'username'
             ,'shoupin'
             ,'quanpin'
             ,'shengri'
             ,'sex'
             ,'phone'
             ,'zhicheng_id'
             ,'xueli_id'
             ,'biye'
             ,'zhuanye'
             ,'worktime'
        ],
        'admincreateall'  =>  [
             'xingming'
             ,'username'
             ,'sex'
             ,'shengri'
             ,'phone'
             ,'shoupin'
             ,'quanpin'
             ,'worktime'
             ,'zhiwu_id'
             ,'zhicheng_id'
             ,'school_id'
             ,'biye'
             ,'zhuanye'
             ,'xueli_id'
        ],
    ];
}
