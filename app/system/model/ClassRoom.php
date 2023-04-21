<?php

namespace app\system\model;

use app\BaseModel;

/**
 * @mixin \think\Model
 */
class ClassRoom extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' =>  'int'
        ,'title' =>   'varchar'
        ,'weizhi' =>  'varchar'
        ,'category_id' => 'int'
        ,'shangke' => 'tinyint'
        ,'status' =>  'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' =>  'varchar'
    ];

    // 查询所有文件
    public function search($srcfrom)
    {
        // 整理条件
        $src = [
            'searchval' => ''
            ,'category_id' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $src['category_id'] = str_to_array($src['category_id']);

        // 查询数据
        $data = $this
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title|weizhi|beizhu', 'like', '%' . $src['searchval'] . '%');
                })
            ->when(count($src['category_id']) > 0, function($query) use($src){
                    $query->where('category_id', 'in', $src['category_id']);
                })
            ->with([
                'crCategory' => function ($query) {
                    $query->field('id, title');
                }
            ])
            ->when($src['all'] == false, function ($query) use($src) {
                $query->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->select();

        return $data;
    }


    // 教室种类数模型关联
    public function  crCategory()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }


    // 性别获取器
    public function getShangkeAttr($value)
    {
        $shangke = [
            '0' => '否'
            ,'1' => '是'
        ];

        $str = '';
        if(isset($shangke[$value]))
        {
            $str = $shangke[$value];
        }else{
            $str = '未知';
        }
        return $str;
    }


}
