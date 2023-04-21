<?php

namespace app\system\model;

use app\BaseModel;

class Fields extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'category_id' => 'int'
        ,'oldname' => 'varchar'
        ,'bianjitime' => 'int'
        ,'newname' => 'varchar'
        ,'extension' => 'varchar'
        ,'fieldsize' => 'int'
        ,'hash' => 'varchar'
        ,'user_group' => 'varchar'
        ,'user_id' => 'int'
        ,'url' => 'varchar'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 编辑时间获取器
    public function getBianjitimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }


    // 查询文件是否重复上传
    public function hasHash($str=""){
    	$hasHash = 0;
    	$list = $this->where('hash', $str)->find();
    	if($list){
    		$hasHash = 1;
    	}
    	return $hasHash;
    }


    // 查询所有文件
    public function search($srcfrom)
    {
        // 整理条件
        $src = [
            'searchval' => ''
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
                    $query->where('oldname', 'like', '%' . $src['searchval'] . '%');
                })
            ->with([
                'flCategory' => function ($query) {
                    $query->field('id, title');
                },
                'flAdmin' => function ($query) {
                    $query->field('id, xingming, school_id')
                        ->with([
                            'adSchool' => function ($q) {
                                $q->field('id, jiancheng');
                            }
                        ]);
                }
            ])
            ->when($src['all'] == false, function ($query) use($src) {
                $query->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->select();

        return $data;
    }

    // 上传人数据关联
    public function  flAdmin()
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'user_id', 'id');
    }


    // 文件种类数模型关联
    public function  flCategory()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }
}
