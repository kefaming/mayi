<?php

namespace app\teach\model;

// 引用数据模型基类
use app\BaseModel;

/**
 * @mixin \think\Model
 */
class BanZhuRen extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'teacher_id' => 'int'
        ,'banji_id' => 'int'
        ,'bfdate' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
    ];


    // 教师关联模型
    public function glAdmin()
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'teacher_id', 'id');
    }


    // 教师关联模型
    public function glBanji()
    {
        return $this->belongsTo(\app\teach\model\Banji::class, 'banji_id', 'id');
    }


    // 班级对应教师
    public function banjiTeachers()
    {
        return $this->hasMany(\app\teach\model\BanZhuRen::class, 'banji_id', 'banji_id');
    }


    // 接任时间获取器
    public function getBfdateAttr($value)
    {
        return date('Y-m-d',$value);
    }


    // 接任时间修改器
    public function setBfdateAttr($value)
    {
        return strtotime($value);
    }


    // 查询任职结束时间
    public function getJieShuAttr()
    {
        $str = '?';

        if($this->glBanji->biye === true)
        {
            $str = $this->glBanji->ruxuenian . '-8-1';
        }else{
            // 根据当前记录时间查询结束时间
            $js = $this
                ->where('banji_id', $this->banji_id)
                ->where('bfdate', '>', $this->getData('bfdate'))
                ->order(['bfdate' => 'asc'])
                ->find();
            if($js)
            {
                $str = $js->bfdate;
            }
        }

        return $str;
    }


    // 根据条件查询学期
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'banji_id' => ''
            ,'teacher_id' => ''
            ,'bfdate' => date("Y-m-d", strtotime("-6 year"))
            ,'enddate' => date("Y-m-d", strtotime('1 day'))
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'bfdate'
            ,'order' => 'desc'
            ,'all' => false
        ];

        $src = array_cover($srcfrom, $src) ;
        $src['banji_id'] = str_to_array($src['banji_id']);
        $src['teacher_id'] = str_to_array($src['teacher_id']);

        // 查询数据
        $data = $this
            ->whereTime('bfdate', 'between', [$src['bfdate'], $src['enddate']])
            ->when(count($src['banji_id']) > 0, function($query) use($src){
                    $query->where('banji_id', 'in', $src['banji_id']);
                })
            ->when(count($src['teacher_id']) > 0, function($query) use($src){
                    $query->where('teacher_id', 'in', $src['teacher_id']);
                })
            ->when(strlen($src['searchval']) > 0, function ($query) use($src) {
                $query->where('teacher_id', 'in', function ($q) use($src) {
                    $q->name('admin')
                        ->where('xingming', 'like', '%'.$src['searchval'].'%')
                        ->field('id');
                });
            })
            ->with(
                [
                    'glAdmin'=>function($query){
                        $query->field('id, xingming');
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


    // 查询教师担任班主任情况
    public function srcTeacher($srcfrom)
    {
        // 整理变量
        $src = [
            'teacher_id' => ''
            ,'time' => time()
        ];
        $src = array_cover($srcfrom, $src) ;

        $data = $this->where('teacher_id', $src['teacher_id'])
            ->where('bfdate', '<', $src['time'])
            ->order(['bfdate'=>'desc'])
            ->with([
                'glBanji' => function ($query) {
                    $query->field('id, ruxuenian, paixu')->append(['banJiTitle', 'biye']);
                }
            ])
            ->append(['jieShu'])
            ->select();

        return $data;
    }


    // 查询教师担任班主任情况
    public function srcTeacherNow($admin_id)
    {
        $teacher = $this->where('teacher_id', $admin_id)
            ->whereTime('bfdate', '<=', time())
            ->order(['bfdate'=>'desc'])
            ->field('banji_id
                ,any_value(teacher_id) as teacher_id
                ,any_value(bfdate) as bfdate')
            ->group('banji_id')
            ->with([
                'banjiTeachers' => function ($query) {
                    $query->field('id, teacher_id, bfdate, banji_id')
                        ->order(['bfdate' => 'desc']);
                }
                ,'glBanji' => function ($query) {
                    $query->field('id, ruxuenian, paixu')->append(['banJiTitle', 'biye']);
                }
            ])
            ->select();

        // 循环写入班级ID
        $banji_ids = array();
        foreach ($teacher as $key => $value) {
            if ($value->glBanji->biye === false && $value->banjiTeachers[0]->teacher_id == $admin_id) {
                $banji_ids[] = $value->banji_id;
            }
        }

        return $banji_ids;
    }


    // 班主任权限
    public function bzrAuth()
    {
        $id = session('user_id');
        $banji_id = array();
        // 获取班主任班级权限
        $bzr_banji_id = $this->srcTeacherNow($id);
        $banji_id = array_merge($banji_id, $bzr_banji_id);
        $banji_id = array_unique($banji_id);
        return $banji_id;
    }

}
