<?php

namespace app\system\model;

use app\BaseModel;

class Category extends BaseModel
{
	// 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'p_id' => 'int'
        ,'status' => 'tinyint'
        ,'paixu' => 'int'
        ,'isupdate' => 'tinyint'
        ,'beizhu' => 'varchar'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 父级类型关联
    public function glPid()
    {
    	return $this->belongsTo(Category::class, 'p_id', 'id');
    }


    // 子类型关联
    public function glCid()
    {
        return $this->hasMany(Category::class, 'p_id', 'id');
    }


    // 查询所有类别
    public function search($srcfrom)
    {
        $src = [
            'p_id'=>''
            ,'searchval'=>''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];

        // 用新值替换初始值
        $src = array_cover($srcfrom, $src) ;

        // 查询数据
        $data = $this
            ->when(strlen($src['p_id']) > 0, function($query) use($src){
                    $query->whereOr('p_id', 'in', $src['p_id']);
                })
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title', 'like', '%' . $src['searchval'] . '%');
                })
            ->with(
                [
                    'glPid'=>function($query){
                        $query->field('id, title');
                    }
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


    // 查询子类别
    public function srcChild($srcfrom)
    {
        $src = [
            'p_id'=>''
            ,'order' => 'asc'
        ];
        $src = array_cover($srcfrom, $src);   # 新值替换旧值

        $child = self::where('p_id', $src['p_id'])
            ->where('status', 1)
            ->field('id, title, p_id, status, paixu, isupdate')
            ->order(['paixu' => $src['order']])
            ->select();

        return $child;
    }


    // 根据ID范围查类别
    public function srcBetweenID($low=0, $high=0)
    {
        $data = $this
            ->whereBetween('id', $low . ',' . $high)
            ->where('status', 1)
            ->field('id, title')
            ->select();
            
        return $data;
    }
}
