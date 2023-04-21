<?php

namespace app\rongyu\model;

// 引用数据模型类
use app\BaseModel;

class JsRongyuCanyu extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'category_id' => 'int'
        ,'rongyu_id' => 'int'
        ,'teacher_id' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 荣誉册关联
    public function rongyu()
    {
    	return $this->belongsTo(\app\rongyu\model\JsRongyu::class, 'rongyu_id', 'id');
    }

    // 教师关联
    public function teacher()
    {
    	return $this->belongsTo(\app\admin\model\Admin::class, 'teacher_id', 'id');
    }

    //搜索课题册
    public function searchCanyu($srcfrom)
    {
        $src = [
            'rongyu_id' => ''
            ,'category_id' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);

        $list = $this
            ->where('rongyu_id', $src['rongyu_id'])
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
                    'rongyu' => function($query){
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
