<?php

namespace app\rongyu\model;

// 引用数据模型基类
use app\BaseModel;

class JsRongyu extends BaseModel
{
	// 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'fzschool_id' => 'int'
        ,'fzshijian' => 'int'
        ,'category_id' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'status' => 'tinyint'
    ];


    //搜索教师获奖荣誉
    public function search($srcfrom)
    {
    	$src = [
            'fzschool_id' => array()
            ,'hjschool_id' => array()
            ,'category_id' => array()
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['fzschool_id'] = str_to_array($src['fzschool_id']);
        $src['hjschool_id'] = str_to_array($src['hjschool_id']);
        $src['category_id'] = str_to_array($src['category_id']);

    	$data = $this
    		->when(count($src['fzschool_id']) > 0, function($query) use($src){
                	$query->where('fzschool_id', 'in', $src['fzschool_id']);
                })
    		->when(count($src['category_id']) > 0, function($query) use($src){
                	$query->where('category_id', 'in', $src['category_id']);
                })
    		->when(strlen($src['searchval']) > 0, function($query) use($src){
                	$query->where('title', 'like', '%' . $src['searchval'] . '%');
                })
            ->with(
                [
                    'fzSchool' => function($query){
                        $query->field('id, jiancheng, jibie_id')
                        ->with(['dwJibie' => function($q){
                            $q->field('id, title');
                        }]);
                    },
                    'lxCategory' => function($query){
                        $query->field('id, title');
                    },

                ]
            )
            ->withCount(['ryInfo' => 'count'])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
    		->select();

    	return $data;
    }


    // 颁奖单位关联
    public function fzSchool()
    {
         return $this->belongsTo(\app\system\model\School::class, 'fzschool_id', 'id');
    }


    // 荣誉类型关联
    public function lxCategory()
    {
         return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }


    // 荣誉信息关联
    public function ryInfo()
    {
    	return $this->hasMany(JsRongyuInfo::class, 'rongyuce_id', 'id');
    }


    // 荣誉级别
    public function getCntAttr()
    {
         return $this->ryInfo->count();
    }


    // 发证时间修改器
    public function setFzshijianAttr($value)
    {
        return strtotime($value);
    }

    // 发证时间获取器
    public function getFzshijianAttr($value)
    {
        $value > 0 ? $value = date('Y-m-d', $value) : $value = "";

        return $value;
    }



}
