<?php
// 命令空间
namespace app\kaohao\model;

// 引用数据模型基类
use \app\BaseModel;

// 引用数据模型
use \app\kaohao\model\Kaohao as kh;

class SearchOne extends BaseModel
{
    // 获取某个学生历次考试成绩
    public function oldChengji($srcfrom)
    {
        // 初始化参数
        $src = array(
            'student_id' => ''
            ,'category_id' => ''
            ,'xueqi_id' => ''
            ,'kaoshi_id' => ''
            ,'searchval' => ''
        );
        $src = array_cover($srcfrom, $src);
        $src['category_id'] = str_to_array($src['category_id']);
        $src['xueqi_id'] = str_to_array($src['xueqi_id']);
        $src['kaoshi_id'] = str_to_array($src['kaoshi_id']);

        if(isset($srcfrom['bfdate']) && strlen($srcfrom['bfdate'])>0)
        {
            $src['bfdate'] = $srcfrom['bfdate'];
        }else{
            $src['bfdate'] = date("Y-m-d",strtotime("-1 year"));
        }

        if(isset($srcfrom['enddate']) && strlen($srcfrom['enddate'])>0)
        {
            $src['enddate'] = $srcfrom['enddate'];
        }else{
            $src['enddate'] = date("Y-m-d",strtotime("+1 week"));
        }

        $kh = new kh;
        $stuCj = $kh->where('student_id',$src['student_id'])
            ->where('kaoshi_id', 'in', function($query) use($src){
                $query->name('kaoshi')
                    ->whereTime('bfdate|enddate', 'between', [$src['bfdate'], $src['enddate']])
                    ->where('luru&status', '0')
                    ->when(count($src['kaoshi_id'])>0, function($w) use($src){
                        $w->where('id', 'in', $src['kaoshi_id']);
                    })
                    ->when(strlen($src['searchval'])>0, function($w) use($src){
                        $w->where('title', 'like', '%' . $src['searchval'] . '%');
                    })
                    ->when(count($src['category_id'])>0, function($w) use($src){
                        $w->where('category_id', 'in', $src['category_id']);
                    })
                    ->when(count($src['xueqi_id'])>0, function($w) use($src){
                        $w->where('xueqi_id', 'in', function($q) use($src){
                            $q->name('xueqi')
                                ->where('category_id', 'in', $src['xueqi_id'])
                                ->field('id');
                        });
                    })
                    ->field('id');
            })
            ->with([
                'ksChengji'=>function($query){
                    $query->field('id, kaohao_id, subject_id, defen, defenlv, bweizhi, xweizhi, qweizhi');
                }
                ,'cjSchool' => function($query){
                    $query->field('id, jiancheng');
                }
                ,'cjKaoshi' => function($query){
                    $query->field('id, title, zuzhi_id, xueqi_id, category_id, bfdate, enddate')
                        ->with([
                            'ksCategory' => function($q){
                                $q->field('id, title');
                            }
                            ,'ksZuzhi' => function($q)
                            {
                                $q->field('id, title, jiancheng');
                            }
                    ]);
                }
            ])
            ->field('id ,kaoshi_id, student_id, ruxuenian, nianji, banji_id, paixu')
            ->append(['banjiTitle', 'banjiFenshuxian'])
            ->select();

        return $stuCj;
    }


    // 根据考号查询成绩
    public function oneKaohaoChengji($id)
    {
        $kh = new kh;
        $stuCj = $kh::where('id', $id)
            ->with([
                'ksChengji'=>function($query){
                    $query
                        ->with([
                            'subjectName'=>function($q){
                                $q->field('id, title');
                            }
                        ])
                        ->field('id, kaohao_id, subject_id, defen, defenlv, bweizhi, xweizhi, qweizhi');
                }
                ,'cjBanji' => function($q){
                    $q->field('id, paixu, ruxuenian')
                        ->append(['numTitle', 'banjiTitle']);
                }
                ,'cjSchool' => function($q){
                    $q->field('id, jiancheng');
                }
                ,'cjStudent' => function($q){
                    $q->field('id, xingming');
                }
                ,'cjKaoshi' => function ($query) {
                    $query->field('id, title, category_id')
                        ->with([
                            'ksCategory' => function ($q) {
                                $q->field('id, title');
                            }
                        ]);
                }
            ])
            ->field('id, kaoshi_id, student_id, ruxuenian, nianji, banji_id, paixu')
            ->append(['banjiTitle', 'banjiFenshuxian'])
            ->find();

        return $stuCj;
    }


    // 根据考号和学科ID查询某学科成绩
    public function srcOneSubjectChengji($id, $subject_id)
    {
        $kh = new kh;
        $stuCj = $kh::where('id', $id)
            ->field('id ,banji_id, school_id, student_id')
            ->with([
                'cjBanji' => function($q){
                    $q->field('id, paixu, ruxuenian')
                        ->append(['numTitle', 'banjiTitle']);
                }
                ,'cjSchool' => function($q){
                    $q->field('id, jiancheng');
                }
                ,'cjStudent' => function($q){
                    $q->field('id, xingming');
                }
            ])
            ->find();

        if(!$stuCj->cjStudent)
        {
            $stu = new \app\student\model\Student;
            $stuinfo = $stu::withTrashed()
                ->where('id', $stuCj->student)
                ->field('id, xingming, sex')
                ->find();
            if(!$stuinfo){
                $stuCj->cjStudent = array(
                    'id' => $stuCj->student
                    ,'xingming' => "真删除"
                );
            } else {
                $stuCj->cjStudent = array(
                    'id' => $stuinfo->id
                    ,'xingming' => $stuinfo->xingming
                );
            }
        }

        $sbj = new \app\teach\model\Subject;
        $sbjinfo = $sbj
            ->where('id', $subject_id)
            ->field('id, title, jiancheng')
            ->find();
        $stuCj->subjectName = $sbjinfo;

        $cj = new \app\chengji\model\Chengji;
        $cjinfo = $cj->where('kaohao_id', $id)
                    ->where('subject_id', $subject_id)
                    ->field('id,subject_id,kaohao_id,defen')
                    ->find();
         $stuCj->chengji = $cjinfo;

        return $stuCj;
    }

}





