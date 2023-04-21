<?php
declare (strict_types = 1);

namespace app\teach\model;

// 引用数据模型基类
use app\BaseModel;

/**
 * @mixin \think\Model
 */
class KeChengBiao extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'xueqi_id' => 'int'
        ,'banji_id' => 'int'
        ,'subject_id' => 'int'
        ,'teacher_id' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
    ];
}
