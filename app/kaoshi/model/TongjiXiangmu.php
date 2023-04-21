<?php

namespace app\kaoshi\model;

// 引用数据模型基类
use \app\BaseModel;

/**
 * @mixin \think\Model
 */
class TongjiXiangmu extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'category_id' => 'int'
        ,'title' => 'varchar'
        ,'biaoshi' => 'varchar'
        ,'paixu' => 'int'
        ,'tongji' => 'tinyint'
        ,'status' => 'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
    ];


    // 查询记录
    public function search($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'category_id' => array()
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['category_id'] = str_to_array($src['category_id']);

        // 查询数据
        $data = $this
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title|biaoshi', 'like', '%' . $src['searchval']. '%');
                })
            ->when(count($src['category_id']) > 0, function($query) use($src){
                    $query->where('category_id', 'in', $src['category_id']);
                })
            ->with([
                'tjxmCategory' => function ($q) {
                    $q->field('id,title');
                }
            ])
            ->when($src['all'] == false, function ($query) use($src) {
                $query->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            // ->fetchsql(true)
            ->select();

        return $data;
    }


    // 统计获取器
    public function getTongjiAttr($value)
    {
        $jg = 0;
        if($value === 1)
        {
            $jg = '参与';
        }else{
            $jg = '不参与';
        }
        return $jg;
    }


    // 查询统计
    public function srcTongji($category_id)
    {
        $data = $this->where('status', 1)
                ->where('tongji', 1)
                ->where('category_id', $category_id)
                ->field('title, biaoshi')
                ->order(['paixu'])
                ->select()
                ->toArray();
        return $data;
    }


    // 类别关联
    public function tjxmCategory()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }
}
