<?php

namespace app\keti\model;

// 引用数据模型基类
use app\BaseModel;


class Lixiang extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'lxshijian' => 'int'
        ,'lxdanwei_id' => 'int'
        ,'category_id' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
        ,'status' => 'tinyint'
    ];


    //搜索课题册
    public function search($srcfrom)
    {
    	$src = [
            'lxdanwei_id' => array()
            ,'category_id' => array()
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $src['lxdanwei_id'] = str_to_array($src['lxdanwei_id']);
        $src['category_id'] = str_to_array($src['category_id']);

    	$data = $this
    		->when(count($src['lxdanwei_id']) > 0, function($query) use($src){
                	$query->where('lxdanwei_id', 'in', $src['lxdanwei_id']);
                })
    		->when(count($src['category_id']) > 0, function($query) use($src){
                	$query->where('category_id', 'in', $src['category_id']);
                })
    		->when(strlen($src['searchval']) > 0, function($query) use($src){
                	$query->where('title', 'like', '%' . $src['searchval'] . '%');
                })
            ->with(
                [
                    'ktCategory' => function($query){
                        $query->field('id, title');
                    },
                    'ktLxdanwei' => function($query){
                        $query->field('id, title');
                    },
                ]
            )
            ->withCount(['ktInfo' => 'lxcount'], 'id')
            ->withCount(['ktInfo' => function($query){
                $query->where('jddengji_id', 'between', [11802, 11803]);
            }])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
    		->select();

    	return $data;
    }


    // 类型关联
    public function ktCategory()
    {
    	return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }


    // 立项单位关联
    public function ktLxdanwei()
    {
    	return $this->belongsTo(\app\system\model\School::class, 'lxdanwei_id', 'id');
    }


    // 课题信息关联
    public function ktInfo()
    {
    	return $this->hasMany(KetiInfo::class, 'lixiang_id', 'id');
    }


    // 立项时间获取器
    public function getLxshijianAttr($value)
    {
    	if ($value>0)
        {
            $value = date('Y-m-d', $value);
        }else{
            $value = "";
        }
        return $value;
    }


    // 立项时间
    public function setLxshijianAttr($value)
    {
    	return strtotime($value);
    }


    // 课题数量
    public function getKtcntAttr()
    {
    	return $this->ktInfo->count();
    }


    // 结题课题数量
    public function getJtcntAttr()
    {
    	return $this->ktInfo->where('jddengji_id', '>', 0)
            ->where('jtshijian', '>', 0)
            ->count();
    }
}
