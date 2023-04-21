<?php

namespace app\system\model;

use app\BaseModel;

class SystemBase extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'sys_title' => 'varchar'
        ,'keywords' => 'varchar'
        ,'description' => 'varchar'
        ,'thinks' => 'varchar'
        ,'danwei' => 'varchar'
        ,'gradelist' => 'varchar'
        ,'classmax' => 'int'
        ,'xuenian' => 'int'
        ,'xueqishang' => 'int'
        ,'xueqixia' => 'int'
        ,'classalias' => 'tinyint'
        ,'teacherrongyu' => 'tinyint'
        ,'teacherketi' => 'tinyint'
        ,'studefen' => 'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 查询系统信息
    static function sysInfo()
    {
        $info = self::where('id', 1)
            ->field('id, keywords, description, thinks, danwei, gradelist, classmax, classalias, xuenian,  studefen, sys_title')
            ->cache(true)
            ->find();

        return $info;
    }


    // 学年节点获取器
    public function getXuenianAttr($value)
    {
        $value = date('n月j日', $value);
        return $value;
    }


    // 学年节点修改器
    public function setXuenianAttr ($value)
    {
        $value = strtotime($value);
        $value = date('Y', time()).'-'.date('m-d', $value);
        $value = strtotime($value);
        return $value;
    }
}
