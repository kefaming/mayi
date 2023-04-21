<?php

namespace app\keti\model;

use app\BaseModel;

class KetiCanyu extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'category_id' => 'int'
        ,'ketiinfo_id' => 'int'
        ,'teacher_id' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 课题册关联
    public function glLixiang()
    {
    	return $this->belongsTo(\app\keti\model\Lixiang::class, 'ketiinfo_id', 'id');
    }


    // 教师关联
    public function teacher()
    {
    	return $this->belongsTo(\app\admin\model\Admin::class, 'teacher_id', 'id');
    }


    //搜索课题册
    public function search($srcfrom)
    {
        $src = [
            'ketiinfo_id' => array()
            ,'category_id' => array()
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $src['ketiinfo_id'] = str_to_array($src['ketiinfo_id']);
        $src['category_id'] = str_to_array($src['category_id']);

        $data = $this
            ->when(count($src['ketiinfo_id']) > 0, function($query) use($src){
                    $query->where('ketiinfo_id', 'in', $src['ketiinfo_id']);
                })
            ->when(count($src['category_id']) > 0, function($query) use($src){
                    $query->where('category_id', 'in', $src['category_id']);
                })
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('teacher_id', 'in', function ($q) use ($src) {
                        $q->name('admin')
                            ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                            ->field('id');
                    });
                })
            ->with(
                [
                    'teacher' => function($query){
                        $query->field('id, xingming, school_id')
                            ->with([
                                'adSchool' => function ($q) {
                                    $q->field('id, jiancheng');
                                }
                            ]);
                    },
                    'glLixiang' => function($query){
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


    //搜索课题册
    public function searchCanyu($srcfrom)
    {
        $src = [
            'ketiinfo_id' => ''
            ,'category_id' => array()
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);

        $list = $this
            ->where('ketiinfo_id', $src['ketiinfo_id'])
            ->where('category_id', $src['category_id'])
            ->with(
                [
                    'teacher' => function($query){
                        $query->field('id, xingming, school_id')
                            ->with([
                                'adSchool' => function ($q) {
                                    $q->field('id, jiancheng');
                                }
                            ]);
                    },
                    'glLixiang' => function($query){
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
        return $list;
    }
}
