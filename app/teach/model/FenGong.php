<?php
namespace app\teach\model;

use app\BaseModel;

/**
 * @mixin \think\Model
 */
class FenGong extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' =>   'int'
        ,'banji_id' =>     'int'
        ,'subject_id' =>   'int'
        ,'teacher_id' =>   'int'
        ,'xueqi_id' => 'int'
        ,'create_time' =>  'int'
        ,'bfdate' => 'int'
        ,'update_time' =>  'int'
        ,'delete_time' =>  'int'
        ,'beizhu' =>  'varchar'
    ];


    // 按条件查询分工
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'update_time'
            ,'order' => 'desc'
            ,'subject_id' => ''
            ,'banji_id' => ''
            ,'xueqi_id' => ''
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $src['subject_id'] = str_to_array($src['subject_id']);
        $src['xueqi_id'] = str_to_array($src['xueqi_id']);
        $src['banji_id'] = str_to_array($src['banji_id']);

        // 查询数据
        $data = $this
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                $query->where('title|jiancheng', 'like', '%' . $src['searchval'] . '%');
            })
            ->when(count($src['subject_id']) > 0, function($query) use($src){
                $query->where('subject_id', 'in', $src['subject_id']);
            })
            ->when(count($src['xueqi_id']) > 0, function($query) use($src){
                $query->where('xueqi_id', 'in', $src['xueqi_id']);
            })
            ->when(count($src['banji_id']) > 0, function($query) use($src){
                $query->where('banji_id', 'in', $src['banji_id']);
            })
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->with([
                'fgXueqi' => function ($query) {
                    $query->field('id, title');
                }
                ,'fgSubject' => function ($query) {
                    $query->field('id, title, jiancheng, lieming');
                }
                ,'fgTeacher' => function ($query) {
                    $query->field('id, xingming');
                }
                ,'fgBanji' => function ($query) {
                    $query->field('id, ruxuenian, paixu')
                        ->append(['banjiTitle']);
                }
            ])
            ->order([$src['field'] => $src['order']])
            ->select();
        return $data;
    }


    // 查教师现任务分工
    public function teacherFengong($teacher_id = 0, $time = 0)
    {
        $time == 0 ? $time = time() : $time;

        // 查询学期
        $xq = new \app\teach\model\Xueqi;
        $xueqi_id = $xq->timeSrcXueqi($time);
        $xueqi_id ? $xueqi_id = $xueqi_id->id : $xueqi_id = 0;

        // 查询任务分工情况
        $data = $this
            ->where('teacher_id', $teacher_id)
            ->where('xueqi_id', $xueqi_id)
            ->where('bfdate', '<', $time)
            ->field('any_value(id) as id
                ,any_value(xueqi_id) as xueqi_id
                ,any_value(banji_id) as banji_id
                ,any_value(subject_id) as subject_id
                ,any_value(teacher_id) as teacher_id
                ,any_value(bfdate) as bfdate')
            ->group('xueqi_id,banji_id,subject_id')
            ->select();

        $list = array();
        foreach ($data as $key => $value) {
            $src = [
                'banji_id' => $value->banji_id
                ,'subject_id' => $value->subject_id
                ,'xueqi_id' => $value->xueqi_id
                ,'time' => $time
            ];
            $dqfg = $this->subjectFengong($src);
            if($value->teacher_id == $dqfg->teacher_id) {
                $list[$value->banji_id][$value->subject_id] = true;
            }
        }

        return $list;
    }


    // 查教师现任务分工
    public function teacherFengongList($teacher_id = 0, $time = 0)
    {
        $time == 0 ? $time = time() : $time;

        // 查询学期
        $xq = new \app\teach\model\Xueqi;
        $xueqi_id = $xq->timeSrcXueqi($time);
        $xueqi_id ? $xueqi_id = $xueqi_id->id : $xueqi_id = 0;

        // 查询任务分工情况
        $data = $this
            ->where('teacher_id', $teacher_id)
            ->where('xueqi_id', $xueqi_id)
            ->where('bfdate', '<', $time)
            ->with([
                'fgXueqi' => function ($query) {
                    $query->field('id, title');
                }
                ,'fgSubject' => function ($query) {
                    $query->field('id, title, jiancheng, lieming');
                }
                ,'fgTeacher' => function ($query) {
                    $query->field('id, xingming');
                }
                ,'fgBanji' => function ($query) {
                    $query->field('id, ruxuenian, paixu')
                        ->append(['banjiTitle']);
                }
            ])
            ->field('any_value(id) as id
                ,any_value(xueqi_id) as xueqi_id
                ,any_value(banji_id) as banji_id
                ,any_value(subject_id) as subject_id
                ,any_value(teacher_id) as teacher_id
                ,any_value(bfdate) as bfdate')
            ->group('xueqi_id,banji_id,subject_id')
            ->select();

        $list = array();
        foreach ($data as $key => $value) {
            $src = [
                'banji_id' => $value->banji_id
                ,'subject_id' => $value->subject_id
                ,'xueqi_id' => $value->xueqi_id
                ,'time' => $time
            ];
            $dqfg = $this->subjectFengong($src);
            if($value->teacher_id == $dqfg->teacher_id) {
                $list[] = [
                    'id' => $value->id,
                    'banji_id' => $value->banji_id,
                    'teacher_id' => $value->teacher_id,
                    'bfdate' => $value->bfdate,
                    'fgSubject' => $value->fgSubject,
                    'fgTeacher' => $value->fgTeacher,
                    'fgBanji' => $value->fgBanji,
                ];
            }
        }

        return $list;
    }


    // 查询班级学科当前分工
    public function subjectFengong($srcfrom)
    {
        // 整理变量
        $src = [
            'banji_id' => 0
            ,'xueqi_id' => 0
            ,'subject_id' => 0
            ,'bfdate' => 0
            ,'time' => 0
        ];
        $src = array_cover($srcfrom, $src);

        $data = $this
            ->where('banji_id', $src['banji_id'])
            ->where('xueqi_id', $src['xueqi_id'])
            ->where('subject_id', $src['subject_id'])
            ->where('bfdate', '<', $src['time'])
            ->field('id, teacher_id, banji_id, subject_id, xueqi_id')
            ->order(['bfdate' => 'desc'])
            ->find();

        return $data;
    }


    // 查询班级现任课教师
    public function banjiFengong($banji_id=0, $time=0)
    {
        $time == 0 ? $time = time() : $time;

        // 查询学期
        $xq = new \app\teach\model\Xueqi;
        $xueqi_id = $xq->timeSrcXueqi($time);
        $xueqi_id ? $xueqi_id = $xueqi_id->id : $xueqi_id = 0;

        // 查询任务分工情况
        $data = $this
            ->where('banji_id', $banji_id)
            ->where('xueqi_id', $xueqi_id)
            ->where('bfdate', '<', $time)
            ->field('any_value(id) as id
                ,any_value(xueqi_id) as xueqi_id
                ,any_value(banji_id) as banji_id
                ,any_value(subject_id) as subject_id
                ,any_value(teacher_id) as teacher_id
                ,any_value(bfdate) as bfdate')
            // ->group('subject_id')
            ->order(['subject_id', 'bfdate' => 'asc'])  # 按时间正序排序
            ->select();
        $list = array();
        // 循环写出分工
        foreach ($data as $key => $value) { # 后接任替换早接任
            // code...
            $list[$value->subject_id] = [
                'subject_id' => $value->subject_id
                ,'teacher_id' => $value->teacher_id
            ];
        }

        return $list;
    }


    // 学期关联
    public function fgXueqi()
    {
        return $this->belongsTo(\app\teach\model\Xueqi::class, 'xueqi_id', 'id');
    }


    // 学科关联
    public function fgSubject()
    {
        return $this->belongsTo(\app\teach\model\Subject::class, 'subject_id', 'id');
    }


    // 教师关联
    public function fgTeacher()
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'teacher_id', 'id')
            ->visible(['id', 'xingming']);
    }


    // 教师关联
    public function fgBanji()
    {
        return $this->belongsTo(\app\teach\model\Banji::class, 'banji_id', 'id');
    }


    // 接任时间获取器
    public function getBfdateAttr($value)
    {
        return date('Y-m-d', $value);
    }


    // 接任时间修改器
    public function setBfdateAttr($value)
    {
        return strtotime($value);
    }



}
