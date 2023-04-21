<?php

namespace app\admin\model;

// 引用用户数据模型
// 引用用户数据模型
use app\BaseModel;

class AuthGroupAccess extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'uid' => 'int'
        ,'group_id' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 根据角色ID查询用户
    public function search($srcfrom)
    {
    	$src = [
            'group_id' => 0
            ,'searchval' => ''
            ,'school_id' => array()
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];

        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['school_id'] = str_to_array($src['school_id']);

        $data = $this
        	->where('group_id', $src['group_id'])
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                $query
                    ->where('uid', 'in', function($q) use($src){
                        $q
                        ->name('admin')
                        ->where('username|xingming', 'like', '%'. $src['searchval']. '%')
                        ->field('id');
                    });
                
            })
            ->when(count($src['school_id']) > 0, function($query) use($src){
                $query
                    ->where('uid', 'in', function($q) use($src){
                        $q
                        ->name('admin')
                        ->where('school_id', 'in', $src['school_id'])
                        ->field('id');
                    });
                
            })
            ->where('uid','<>', session('userid'))
            ->where('uid', '>', 2)
        	->with([
        		'jsUser' => function($query) use($src) {
        			$query->field('id, xingming, school_id, username')
        				->with([
        					'adSchool' => function($q){
        						$q->field('id, title, jiancheng');
        					}
        				]);
        		}
        	])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
        	->select();

        return $data;
    }
    

    // 考试设置关联表
    public function jsUser()
    {
        return $this->belongsTo(Admin::class, 'uid', 'id');
    }
}
