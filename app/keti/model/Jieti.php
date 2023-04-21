<?php

namespace app\keti\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Jieti extends Model
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'shijian' => 'int'
        ,'danwei_id' => 'int'
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
            'danwei_id' => array()
            ,'shijian' => array()
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $src['danwei_id'] = str_to_array($src['danwei_id']);

    	$data = $this
    		->when(count($src['danwei_id']) > 0, function($query) use($src){
                	$query->where('danwei_id', 'in', $src['danwei_id']);
                })
    		->when(strlen($src['searchval']) > 0, function($query) use($src){
                	$query->where('title', 'like', '%' . $src['searchval'] . '%');
                })
            ->with(
                [
                    'glDanwei' => function($query){
                        $query->field('id, title');
                    },
                ]
            )
            ->withCount(['ktInfo' => 'count'])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
    		->select();

    	return $data;
    }


    // 结题单位关联
    public function glDanwei()
    {
    	return $this->belongsTo(\app\system\model\School::class, 'danwei_id', 'id');
    }


    // 课题信息关联
    public function ktInfo()
    {
    	return $this->hasMany('KetiInfo', 'jieti_id', 'id')
    		->where('jddengji_id', 'between', [11802, 11803]);
    }


    // 立项时间获取器
    public function getShijianAttr($value)
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
    public function setShijianAttr($value)
    {
    	return strtotime($value);
    }
}
