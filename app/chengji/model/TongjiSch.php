<?php
namespace app\chengji\model;
// 引用基类
use \app\BaseModel;
// 引用学生成绩统计类
use app\chengji\model\Tongji as TJ;

/**
 * @mixin think\Model
 */
class TongjiSch extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' =>  'int'
        ,'kaoshi_id' =>   'int'
        ,'ruxuenian' =>   'int'
        ,'subject_id' =>  'int'
        ,'stu_cnt' => 'int'
        ,'chengji_cnt' => 'int'
        ,'sum' => 'decimal'
        ,'avg' => 'decimal'
        ,'defenlv' => 'decimal'
        ,'biaozhuncha' => 'decimal'
        ,'youxiu' =>  'int'
        ,'jige' =>    'int'
        ,'max' => 'decimal'
        ,'min' => 'decimal'
        ,'q1' =>  'decimal'
        ,'q2' =>  'decimal'
        ,'q3' =>  'decimal'
        ,'zhongshu' =>    'varchar'
        ,'zhongweishu' => 'decimal'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'status' =>  'tinyint'
    ];


    // 统计参加本次考试所有学校成绩
    public function tjSchool($kaoshi_id)
    {
        $src['kaoshi_id'] = $kaoshi_id;
        // 查询要统计的学校
        $cy = new \app\kaohao\model\SearchCanYu;
        $more = new \app\kaohao\model\SearchMore;
        // 查询要统计的年级
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        $njList = $ksset->srcGrade($src);

        // 实例化学生成绩统计类
        $tj = new TJ;
            foreach ($njList as $k => $nianji) {
                $src = [
                    'kaoshi_id' => $kaoshi_id
                    ,'ruxuenian' => $nianji['ruxuenian']
                    ,'auth' => [
                        'check' => false
                    ]
                ];
                $src['banji_id'] = array_column($cy->class($src), 'id');
                $subject = $ksset->srcSubject($src);
                $temp = $more->srcChengjiList($src);
                $temp = $tj->tongjiSubject($temp, $subject);
                foreach ($temp['cj'] as $k => $cj) {
                    // 查询该班级该学科成绩是否存在
                    $tongjiJg = $this->where('kaoshi_id', $src['kaoshi_id'])
                        ->where('ruxuenian', $nianji['ruxuenian'])
                        ->where('subject_id', $cj['id'])
                        ->find();
                    if($tongjiJg)
                    {
                        $tongjiJg->kaoshi_id = $src['kaoshi_id'];
                        $tongjiJg->ruxuenian = $nianji['ruxuenian'];
                        $tongjiJg->subject_id = $cj['id'];
                        $tongjiJg->stu_cnt = $cj['stu_cnt'];
                        $tongjiJg->chengji_cnt = $cj['chengji_cnt'];
                        $tongjiJg->sum = $cj['sum'];
                        $tongjiJg->avg = $cj['avg'];
                        $tongjiJg->biaozhuncha = $cj['biaozhuncha'];
                        $tongjiJg->youxiu = $cj['youxiu'];
                        $tongjiJg->jige = $cj['jige'];
                        $tongjiJg->max = $cj['max'];
                        $tongjiJg->min = $cj['min'];
                        $tongjiJg->q1 = $cj['sifenwei'][0];
                        $tongjiJg->q2 = $cj['sifenwei'][1];
                        $tongjiJg->q3 = $cj['sifenwei'][2];
                        $tongjiJg->zhongshu = $cj['zhongshu'];
                        $tongjiJg->zhongweishu = $cj['zhongweishu'];
                        $tongjiJg->defenlv = $cj['defenlv'];
                        $data = $tongjiJg->save();
                    }else{
                        // 重新组合统计结果
                        $tongjiJg = [
                            'kaoshi_id' => $src['kaoshi_id']
                            ,'ruxuenian' => $nianji['ruxuenian']
                            ,'subject_id' => $cj['id']
                            ,'stu_cnt' => $cj['stu_cnt']
                            ,'chengji_cnt' => $cj['chengji_cnt']
                            ,'sum' => $cj['sum']
                            ,'avg' => $cj['avg']
                            ,'biaozhuncha' => $cj['biaozhuncha']
                            ,'youxiu' => $cj['youxiu']
                            ,'jige' => $cj['jige']
                            ,'max' => $cj['max']
                            ,'min' => $cj['min']
                            ,'q1' => $cj['sifenwei'][0]
                            ,'q2' => $cj['sifenwei'][1]
                            ,'q3' => $cj['sifenwei'][2]
                            ,'zhongshu' => $cj['zhongshu']
                            ,'zhongweishu' => $cj['zhongweishu']
                            ,'defenlv' => $cj['defenlv']
                        ];
                        $data = $this::create($tongjiJg);
                    }
                }
        }

        return true;
    }


    // 根据条件查询学校成绩
    public function search($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => ''
            ,'ruxuenian' => ''
        );
        $src = array_cover($srcfrom, $src);
        $kaoshi_id = $src['kaoshi_id'];

        $tongjiJg = $this
            ->where('kaoshi_id', $src['kaoshi_id'])
            ->where('ruxuenian', $src['ruxuenian'])
            ->field('kaoshi_id, ruxuenian')
            ->with([
                'schJieguo' => function ($query) use ($src) {
                    $query->field('stu_cnt, chengji_cnt, sum, avg, defenlv, biaozhuncha, youxiu, jige, max, min, q1, q2, q3, zhongshu, zhongweishu,  subject_id, ruxuenian')
                        ->where('kaoshi_id', $src['kaoshi_id'])
                        ->with([
                            'schSubject' => function($query){
                                $query->field('id, lieming, jiancheng');
                            },
                        ])
                        ->order(['subject_id']);
                }
            ])
            ->group('kaoshi_id, ruxuenian')
            ->select();

        // 重组数据
        $data = array();
        foreach ($tongjiJg as $key => $value) {
            $data['all'] = [
                'id' => $value->id
                ,'school_jiancheng' => '全区'
                ,'school_paixu' => 9999
            ];
            foreach ($value->schJieguo as $k => $val) {
                if ($val->subject_id > 0) {
                    $data['all']['chengji'][$val->schSubject->lieming] = [
                        'stu_cnt' => $val->stu_cnt
                        ,'chengji_cnt' => $val->chengji_cnt
                        ,'sum' => $val->sum
                        ,'avg' => $val->avg
                        ,'defenlv' => $val->defenlv
                        ,'biaozhuncha' => $val->biaozhuncha
                        ,'youxiu' => $val->youxiu
                        ,'youxiulv' => $val->youxiulv
                        ,'jige' => $val->jige
                        ,'jigelv' => $val->jigelv
                        ,'max' => $val->max
                        ,'min' => $val->min
                        ,'q1' => $val->q1
                        ,'q2' => $val->q2
                        ,'q3' => $val->q3
                        ,'zhongshu' => $val->zhongshu
                        ,'zhongweishu' => $val->zhongweishu
                        ,'chashenglv' => $val->chashenglv
                        ,'canshilv' => $val->canshilv
                    ];
                }else{
                    $data['all']['quanke'] = [
                        'avg' => $val->avg
                        ,'jigelv' => $val->jigelv
                    ];
                }
            }
        }
        return $data;
    }



    // 成绩排序
    public function schOrder($kaoshi_id)
    {
        $src = array('kaoshi_id' => $kaoshi_id);
        // 实例化学生成绩统计类
        $cy = new \app\kaohao\model\SearchCanYu;
        $more = new \app\kaohao\model\SearchMore;
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $cj = new \app\chengji\model\Chengji;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        $nianji = $ksset->srcGrade($src);

        // 循环年级
        $data = array();
        foreach ($nianji as $njkey => $value) {
            // 获取参加考试班级
            $src['ruxuenian'] = $value['ruxuenian'];
            $subject = $ksset->srcSubject($src);
            $banji = $cy->class($src);
            $col = [
                'qpaixu'
                ,'qweizhi'
            ];

            // 获取成绩
            $srcfrom = [
                'kaoshi_id' => $kaoshi_id
                ,'ruxuenian' => $value['ruxuenian']
                ,'banji_id' => array_column($banji, 'id')
            ];
            $temp = $more->srcChengjiSubject($srcfrom);
            // 循环计算成绩排序
            foreach ($temp as $key => $value) {
                $cj->saveOrder($value, $col);
            }
        }
        return true;
    }


    // 学科关联
    public function schSubject()
    {
        return $this->belongsTo('\app\teach\model\Subject', 'subject_id', 'id');
    }


    // 成绩统计结果关联
    public function schJieguo()
    {
        $data = $this->hasMany('\app\chengji\model\TongjiSch', 'ruxuenian', 'ruxuenian')
            ->append(['youxiulv', 'jigelv', 'chashenglv', 'canshilv']);
        return $data;
    }


    // 获取优秀率
    public function getYouxiulvAttr()
    {
        $youxiu = null;
        $ycnt = $this->youxiu;
        $cjcnt = $this->chengji_cnt;
        if ($cjcnt > 0) {
            $youxiu = round($ycnt / $cjcnt * 100, 2);
        }

        return $youxiu;
    }


    // 获取优秀率
    public function getJigelvAttr()
    {
        $jige = null;
        $jcnt = $this->jige;
        $cjcnt = $this->chengji_cnt;
        if ($cjcnt > 0) {
            $jige = round($jcnt / $cjcnt * 100, 2);
        }

        return $jige;
    }


    // 差生率
    function getChashenglvAttr()
    {
        $cjCnt = $this->chengji_cnt;
        $chaCnt = $cjCnt - $this->jige;
        $chalv = null;
        if ($cjCnt > 0) {
            $chalv = round($chaCnt / $cjCnt * 100, 2);
        }

        return $chalv;
    }


    // 参试率
    function getCanshilvAttr()
    {
        $cjCnt = $this->chengji_cnt;
        $stu_cnt = $this->stu_cnt;
        $canshilv = null;
        if ($stu_cnt > 0) {
            $canshilv = round($cjCnt / $stu_cnt * 100, 2);
        }

        return $canshilv;
    }

}
