<?php

namespace app\kaoshi\model;

// 引用数据模型基类
use \app\BaseModel;

/**
 * @mixin \think\Model
 */
class LuruFengong extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'kaoshi_id' => 'int'
        ,'admin_id' => 'int'
        ,'banji_id' => 'int'
        ,'subject_id' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 查询统计记录
    public function search($srcfrom)
    {
        $src = [
            'kaoshi_id' => ''
            ,'banji_id' => ''
            ,'subject_id' => ''
            ,'ruxuenian' => ''
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src) ;
        $src['kaoshi_id'] = str_to_array($src['kaoshi_id']);
        $src['banji_id'] = str_to_array($src['banji_id']);
        $src['subject_id'] = str_to_array($src['subject_id']);

        $logList = self::where('kaoshi_id', 'in', $src['kaoshi_id'])
			->when(count($src['banji_id']) > 0, function($query) use($src){
                $query->where('banji_id', 'in', $src['banji_id']);
            })
            ->when(count($src['subject_id']) > 0, function($query) use($src){
                $query->where('subject_id', 'in', $src['subject_id']);
            })
			->when(strlen($src['searchval']) > 0, function($query) use($src){
                $query->where('admin_id', 'in', function ($q) use($src) {
                    $q->name('admin')
                        ->where('xingming', 'like', '%'. $src['searchval'] . '%')
                        ->field('id');
                });
            })
            ->when(strlen($src['ruxuenian']) > 0, function($query) use($src){
                $query->where('banji_id', 'in', function ($q) use($src) {
                    $q->name('banji')
                        ->where('ruxuenian', $src['ruxuenian'])
                        ->field('id');
                });
            })
            ->with([
                'fgKaoshi' => function ($query) {
                    $query->field('id, title');
                }
                ,'fgBanji' => function ($query) {
                    $query->field('id, ruxuenian, paixu, school_id')
                    	->with([
                    		'glSchool' => function($q){
		                        $q->field('id, title, jiancheng');
		                    },
                    	])
                    	->append(['banjiTitle']);
                }
                ,'fgSubject' => function ($query) {
                    $query->field('id, title, jiancheng');
                }
                ,'fgAdmin' => function ($query) {
                    $query->field('id, xingming');
                }
            ])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->select();

        return $logList;
    }


    // 根据教师ID查询可以录入成绩的班级
    public function srcMyLuruBase($srcfrom)
    {
        $src = [
            'kaoshi_id' => ''
            ,'school_id' => ''
            ,'ruxuenian' => ''
        ];
        $src = array_cover($srcfrom, $src) ;

        // 查询是否有录入分工，如果有就按分工来出示列表，否则全部出示
        $admin_id = session('user_id');
        $all = $this->where('kaoshi_id', $src['kaoshi_id'])->select();
        $count = $all->count();
        $data = [['banji_id' => 0, 'subject_id' => 0]];

        $list = $this->when($admin_id > 2, function ($query) use($admin_id){
                    $query->where('admin_id', $admin_id);
                })
                ->where('kaoshi_id', $src['kaoshi_id'])
                ->when(strlen($src['school_id']) > 0 || strlen($src['ruxuenian']) > 0, function ($query) use ($src) {
                    $query->where('banji_id', 'in', function ($q) use($src){
                        $q->name('banji')
                            ->when(strlen($src['school_id']) > 0, function($x) use ($src){
                                $x->where('school_id', $src['school_id']);
                            })
                            ->when(strlen($src['ruxuenian']) > 0, function($x) use ($src){
                                $x->where('ruxuenian', $src['ruxuenian']);
                            })
                            ->field('id');
                    });
                })
                ->field('banji_id, subject_id')
                ->select();

        if($admin_id == 1 || $admin_id == 2)
        {
            $data = array();
        }else{
            if($count == 0)
            {
                $data = array();
            }else{
                if(!$list->isEmpty())
                {
                    $data = $list->toArray();
                }
            }
        }

        return $data;
    }


    // 查询可以录入成绩的班级
    public function srcMyLuruBanji($srcfrom)
    {
        $src = [
            'kaoshi_id' => ''
            ,'school_id' => ''
            ,'ruxuenian' => ''
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $list = $this->srcMyLuruBase($src);
        $src['banji_id'] = array_unique(array_column($list, 'banji_id'));
        
        // 获取录入班级后，到参加考试班级中搜索一下
        $khSrc = new \app\kaohao\model\SearchCanYu;
        $bj = $khSrc->class($src);
        return $bj;
    }


    // 查询可以录入成绩的班级
    public function srcMyLuruSubject($srcfrom)
    {
        $src = [
            'kaoshi_id' => ''
            ,'school_id' => ''
            ,'ruxuenian' => ''
        ];
        $src = array_cover($srcfrom, $src) ;
        $list = $this->srcMyLuruBase($src);
        $src['subject_id'] = array_unique(array_column($list, 'subject_id'));
        // 获取录入班级后，到参加考试班级中搜索一下
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $sbj = $ksset->srcSubject($src);
        return $sbj;
    }


    // 获取当前用户录入成绩的权限
    public function auth($srcfrom)
    {
        if(session('user_id') == 1 || session('user_id') == 2) {
            return true;
        }

        $src = [
            'kaoshi_id' => -1
            ,'subject_id' => -1
            ,'banji_id' => -1
        ];
        $src = array_cover($srcfrom, $src) ;
        $cnt = $this
            ->where('kaoshi_id', $src['kaoshi_id'])
            ->count();
        $data = false;
        if ($cnt > 0) {
            $list = $this
            ->where('kaoshi_id', $src['kaoshi_id'])
            ->where('subject_id', $src['subject_id'])
            ->where('banji_id', $src['banji_id'])
            ->where('admin_id', session('user_id'))
            ->find();
            if($list && $cnt > 0){
                $data = true;
            }
        }else{
            $data = true;
        }

        return $data;
    }


    // 考试关联表
    public function fgKaoshi()
    {
        return $this->belongsTo(\app\kaoshi\model\Kaoshi::class, 'kaoshi_id', 'id');
    }


    // 班级关联表
    public function fgBanji()
    {
        return $this->belongsTo(\app\teach\model\Banji::class, 'banji_id', 'id');
    }


    // 学科关联表
    public function fgSubject()
    {
        return $this->belongsTo(\app\teach\model\Subject::class, 'subject_id', 'id');
    }


    // 考试关联表
    public function fgAdmin()
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'admin_id', 'id');
    }


}
