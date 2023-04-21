<?php

namespace app\admin\model;

// 引用用户数据模型
use app\BaseModel;

class AuthGroup extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'rules' => 'varchar'
        ,'miaoshu' => 'varchar'
        ,'status' => 'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 查询所有角色
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'searchval' => ''
            ,'status' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);

        // 查询数据
        $data = $this
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title', 'like', '%' . $src['searchval'] . '%');
                })
            ->when($src['all'] == true, function ($query) {
                $query->field('id');
            })
            ->order(['id'])
            ->select();

        return $data;
    }
}
