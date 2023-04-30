<?php

namespace app\chengji\model;

// 引用基类
use \app\BaseModel;


class Chengji extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' =>  'int'
        ,'kaohao_id' =>   'int'
        ,'user_id' => 'int'
        ,'user_group' =>  'varchar'
        ,'subject_id' =>  'int'
        ,'defen' =>   'decimal'
        ,'defenlv' => 'decimal'
        ,'bpaixu' =>  'int'
        ,'bweizhi' => 'decimal'
        ,'xpaixu' =>  'int'
        ,'xweizhi' => 'decimal'
        ,'qpaixu' =>  'int'
        ,'qweizhi' => 'decimal'
        ,'biaozhunfen' => 'decimal'
        ,'create_time' =>'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' =>  'varchar'
        ,'status' =>  'tinyint'
    ];


    // 学科关联
    public function subjectName()
    {
        return $this->belongsTo('\app\teach\model\Subject','subject_id','id');
    }


    // 学科关联
    public function cjAdmin()
    {
        return $this->belongsTo('\app\admin\model\Admin','user_id','id');
    }


    // 考号关联
    public function cjKaohao()
    {
        return $this->belongsTo('\app\kaohao\model\Kaohao','kaohao_id','id');
    }


    // 查询成绩
    public function searchBase($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => ''
            ,'banji_id' => array()
            ,'subject_id' => ''
            ,'searchval' => ''
            ,'user_id' => ''
            ,'user_group' =>''
        );
        $src = array_cover($srcfrom, $src) ;
        $src['kaoshi_id'] = str_to_array($src['kaoshi_id']);
        $src['banji_id'] = str_to_array($src['banji_id']);
        $src['subject_id'] = str_to_array($src['subject_id']);

        $khid = array();
        if(count($src['kaoshi_id']) > 0 || count($src['banji_id']) > 0 || strlen($src['searchval']) > 0)
        {
            $kh = new \app\kaohao\model\Kaohao;
            $khid = $kh
                ->when(count($src['kaoshi_id']) > 0, function ($query) use($src){
                    $query->where('kaoshi_id', 'in', $src['kaoshi_id']);
                })
                ->when(count($src['banji_id']) > 0, function ($query) use($src){
                    $query->where('banji_id', 'in', $src['banji_id']);
                })
                ->when(strlen($src['searchval']) > 0, function ($query) use($src){
                    $query->where('student_id', 'in', function ($q) use($src) {
                        $q->name('student')
                            ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                            ->field('id');
                    });
                })
                ->column('id');
        }

        $cjList = $this
            ->when(count($src['kaoshi_id']) == 0 && count($src['banji_id']) ==0, function($query) {
                $query->whereMonth('update_time');
            })
            ->when(strlen($src['user_id']) > 0, function ($query) use($src){
                $query->where('user_id', $src['user_id']);
            })
            ->when(count($src['subject_id']) > 0, function ($query) use ($src) {
                $query->where('subject_id', 'in', $src['subject_id']);
            })
            ->when(count($khid) > 0, function ($query) use ($khid) {
                $query->where('kaohao_id', 'in', $khid);
            })
            ->with([
                'subjectName' => function($query){
                    $query->field('id, title, lieming');
                }
                ,'cjKaohao' => function($query){
                    $query->field('id, kaoshi_id, school_id, ruxuenian, nianji, banji_id, paixu, student_id')
                        ->append(['banjiTitle'])
                        ->with([
                            'cjSchool' => function($query){
                                $query->field('id, jiancheng, paixu');
                            }
                            ,'cjStudent' => function($query){
                                $query->field('id, xingming, sex');
                            }
                            ,'cjKaoshi' => function($query){
                                $query->field('id, title');
                            }
                        ]);
                }
            ])
            ->select();

        return $cjList;
    }


    // 列出已录成绩列表
    public function searchLuru($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => ''
            ,'banji_id' => array()
            ,'subject_id' => ''
            ,'searchval' => ''
            ,'user_id' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        );
        $src = array_cover($srcfrom, $src);

        $cjList = $this
            ->when($src['user_id'] > 2, function ($query) use($src){
                $query->where('user_id', $src['user_id']);
            })
            ->when(strlen($src['subject_id']) > 0, function ($query) use($src){
                $query->where('subject_id', $src['subject_id']);
            })
            ->where('kaohao_id', 'in', function ($query) use ($src) {
                $query
                    ->name('kaohao')
                    ->where('kaoshi_id', 'in', function ($q) use ($src) {
                        $q->name('kaoshi')
                            ->where('status&luru', 1)
                            ->when(strlen($src['kaoshi_id']) > 0, function ($q) use ($src) {
                                $q->where('kaoshi_id', $src['kaoshi_id']);
                            })
                            ->when(count($src['banji_id']) > 0, function ($query) use($src) {
                                $query->where('banji_id', 'in', $src['banji_id']);
                            })
                            ->when(strlen($src['searchval']) > 0, function ($query) use($src){
                                $query->where('student_id', 'in', function ($q) use($src) {
                                    $q->name('student')
                                        ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                                        ->field('id');
                                });
                            })
                            ->field('id');
                    })
                    ->field('id');
            })
            ->with([
                'subjectName' => function($query){
                    $query->field('id, title, lieming');
                }
                ,'cjKaohao' => function($query){
                    $query->field('id, kaoshi_id, school_id, ruxuenian, nianji, banji_id, paixu, student_id')
                        ->append(['banjiTitle'])
                        ->with([
                            'cjSchool' => function($query){
                                $query->field('id, jiancheng, paixu');
                            }
                            ,'cjStudent' => function($query){
                                $query->field('id, xingming, sex, xuehao');
                            }
                            ,'cjKaoshi' => function($query){
                                $query->field('id, title');
                            }
                        ]);
                }
            ])
            ->select();

        // 重新整理成绩
        $data = array();
        foreach ($cjList as $key => $value) {
            $data[$key]['id'] = $value->id;
            if($value->cjKaohao)
            {
                if($value->cjKaohao->cjKaoshi)
                {
                    $data[$key]['kaoshi_title'] = $value->cjKaohao->cjKaoshi->title;
                    $data[$key]['kaoshi_id'] =  $value->cjKaohao->cjKaoshi->id;
                }
                $data[$key]['kaohao_id'] = $value->cjKaohao->id;
                $data[$key]['xuehao'] = $value->cjKaohao->xuehao;
                $data[$key]['school_jiancheng'] = $value->cjKaohao->cjSchool->jiancheng;
                $data[$key]['school_id'] = $value->cjKaohao->cjSchool->paixu;
                $data[$key]['banji_title'] = $value->cjKaohao->banjiTitle;
                $data[$key]['banji_id'] = $value->cjKaohao->banji_id;
                $value->cjKaohao->cjStudent ? $data[$key]['student_name']=$value->cjKaohao->cjStudent->xingming : $data[$key]['student_name']= '';
            }
            if($value->subjectName)
            {
                $data[$key]['subject_title'] = $value->subjectName->title;
                $data[$key]['subject_id'] = $value->subjectName->id;
                $data[$key]['subject_lieming'] = $value->subjectName->lieming;
            }
            $data[$key]['defen'] = $value->defen * 1;
            $data[$key]['beizhu'] = $value->beizhu;
            $data[$key]['status'] = $value->status;
            $data[$key]['update_time'] = $value->update_time;

        }

        if ($src['order'] == 'asc') {
            $src['order'] = SORT_ASC;
        } else {
            $src['order'] = SORT_DESC;
        }
        // halt($src['order']);

        // $data = \app\facade\Tools::sortArrByManyField($data, $src['field'], $src['order']);
        $data = \app\facade\Tools::sortArrByManyField($data, 'banji_id', SORT_ASC, 'xuehao', SORT_ASC);

        if($src['all'] != true)
        {
            $start = ($src['page'] - 1) * $src['limit'];
            $data = array_slice($data, $start, $src['limit']);
        }

        return $data;

    }


    // 列出已录成绩列表
    public function searchTeacher($srcfrom)
    {
        $cjList = $this->searchBase($srcfrom);

        // 重新整理成绩
        $data = array();
        foreach ($cjList as $key => $value) {
            $data[$key] = [
                'id' => $value->id
                ,'kaohao_id' => $value->cjKaohao->id
                ,'student' => $value->cjKaohao->cjStudent->xingming
                ,'subject_title' => $value->subjectName->title
                ,'subject_id' => $value->subjectName->id
                ,'banji_title' => $value->cjKaohao->banjiTitle
                ,'subject_lieming' => $value->subjectName->lieming
                ,'defen' => $value->defen
                ,'defenlv' => $value->defenlv
                ,'bpaixu' => $value->bpaixu
                ,'bweizhi' => $value->bweizhi
                ,'xpaixu' => $value->xpaixu
                ,'xweizhi' => $value->xweizhi
                ,'qpaixu' => $value->qpaixu
                ,'qweizhi' => $value->qweizhi
                ,'biaozhunfen' => $value->biaozhunfen
            ];
            $value->cjKaohao->cjStudent ? $data[$key]['student_name'] = $value->cjKaohao->cjStudent->xingming : $data[$key]['student_name'] = '';
        }

        return $data;
    }


    // 根据条件查询学生所有学科成绩
    public function search($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => ''
            ,'banji_id' => array()
            ,'searchval' => ''
            ,'auth' => [
                'check' => true
                ,'banji_id' => array()
            ]
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'asc'
            ,'all' => false
        );
        $src = array_cover($srcfrom, $src);

        // 实例化考号数据模型
        $khSrc = new \app\kaohao\model\SearchMore;
        $chengjilist = $khSrc->srcChengjiList($src);

        return $chengjilist;
    }


    // 成绩排序
    public function saveOrder($cj, $col)
    {
        $cnt = count($cj);      # 获取元素数量
        if ($cnt == 0) {
            return true;
        }

        arsort($cj);
        $data = array();    # 新数据容器
        $last = '-a';
        $rank = 0;
        $i = 0;
        foreach ($cj as $key => $value) {
            $i ++;  # 记录现在记录个数
            if($last != $value)  # 如果当前值与上一个值不相等，名次等于当前记录号,否则与上一条记录相同
            {
                $rank = $i;
            }
            $last = $value;
            if($cnt == 1)
            {
                $data[$key]['id'] = $value;
                $data[$key][$col[0]] = 1;
                $data[$key][$col[1]] = 100;
            }else{
                $data[$key]['id'] = $key;
                $data[$key][$col[0]] = $rank;
                $data[$key][$col[1]] = round(($cnt - $rank) / ($cnt - 1) * 100, 2);
            }
        }

        $temp = $this->saveAll($data);

        return true;
    }
}
