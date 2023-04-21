<?php

namespace app\teach\model;

use app\BaseModel;


class Xueqi extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'xuenian' => 'varchar'
        ,'category_id' => 'int'
        ,'bfdate' => 'int'
        ,'enddate' => 'int'
        ,'status' => 'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
    ];


    // 开始时间修改器
    public function setBfdateAttr($value)
    {
        return strtotime($value);
    }

    // 开始时间获取器
    public function getBfdateAttr($value)
    {
        return date('Y-m-d', $value);
    }

    // 结束时间修改器
    public function setEnddateAttr($value)
    {
        return strtotime($value);
    }

    // 结束时间获取器
    public function getEnddateAttr($value)
    {
        return date('Y-m-d', $value);
    }

    // 分类关联
    public function glCategory()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }

    // 根据条件查询学期
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'category' => ''
            ,'searchval' => ''
            ,'status' => ''
            ,'bfdate' => date("Y-m-d", strtotime("-6 year"))
            ,'enddate' => date("Y-m-d", strtotime('4 year'))
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src) ;

        // 查询数据
        $data = $this
            ->whereTime('bfdate|enddate', 'between', [$src['bfdate'], $src['enddate']])
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title|xuenian', 'like', '%' . $src['searchval'] . '%');
                })
            ->when(strlen($src['category']) > 0, function($query) use($src){
                    $query->where('category', $src['category']);
                })
            ->when(strlen($src['status']) > 0, function($query) use($src){
                    $query->where('status', $src['status']);
                })
            ->with(
                [
                    'glCategory'=>function($query){
                        $query->field('id, title');
                    },
                ]
            )
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->select();

        return $data;
    }


    // 获取最近6个学期
    public function lastXueqi()
    {
        $list = $this
          ->limit(6)
          ->order(['bfdate'=>'desc'])
          ->field('id,title')
          ->select();
        return $list;
    }


    // 根据时间查询学期
    public function timeSrcXueqi($time = 0)
    {
        if($time == 0)
        {
            $time = time();
        }

        $data = $this
            ->where('bfdate', '<', $time)
            ->where('enddate', '>', $time)
            ->cache(true)
            ->find();
        return $data;
    }
}
