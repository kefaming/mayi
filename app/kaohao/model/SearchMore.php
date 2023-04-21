<?php
// 命令空间
namespace app\kaohao\model;

// 引用数据模型基类
use \app\BaseModel;

// 引用数据模型
use \app\kaohao\model\Kaohao as kh;

class SearchMore extends BaseModel
{
    /*
    * 以班级为单位，列出本次考试成绩原始数据；
    * 其它数据以此方法为基础进行数据整理
    */
    public function search($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'banji_id' => array()
            ,'searchval' => ''
            ,'auth' => [
                'check' => true
                ,'banji_id' => array()
            ]
        );

        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['banji_id'] = str_to_array($src['banji_id']);


        // 查询成绩
        $kh = new kh();
        $data = $kh->where('kaoshi_id', $src['kaoshi_id'])
            ->field('id, school_id, student_id, ruxuenian, paixu, kaoshi_id, nianji, banji_id')
            ->where('banji_id', 'in', $src['banji_id'])
            ->when($src['auth']['check'] == true, function ($query) use($src) {
                    $query->where('banji_id', 'in', $src['auth']['banji_id']);
                })
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                $query->where(function($w) use ($src){
                    $w
                    ->whereOr('student_id', 'in', function($q)use($src){
                        $q->name('student')
                            ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                            ->field('id');
                    });
                });
            })
            ->with([
                'ksChengji' => function($query){
                    $query->field('id, kaohao_id, subject_id, defen, defenlv, bpaixu, xpaixu, qpaixu, bweizhi, xweizhi, qweizhi, biaozhunfen')
                        ->with([
                            'subjectName' => function ($q) {
                                $q->field('id, title, jiancheng, lieming');
                            }
                        ]);
                }
                ,'cjSchool' => function($query){
                    $query->field('id, jiancheng');
                }
                ,'cjStudent' => function($query){
                    $query->field('id, xingming, sex, shengri');
                }
            ])

            ->append(['banjiTitle', 'banTitle'])
            ->select();


        return $data;
    }


    /*
    * 以班级为单位，列出本次考试成绩原始数据；
    * 其它数据以此方法为基础进行数据整理
    */
    public function searchLuruBase($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'banji_id' => array()
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'asc'
            ,'all' => false
        );

        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['banji_id'] = str_to_array($src['banji_id']);

        // 查询成绩
        $kh = new kh();
        $data = $kh->where('kaoshi_id', $src['kaoshi_id'])
            ->field('id, school_id, student_id, ruxuenian, paixu, kaoshi_id, nianji, banji_id')
            ->where('banji_id', 'in', $src['banji_id'])
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                $query->where(function($w) use ($src){
                    $w
                    ->whereOr('student_id', 'in', function($q)use($src){
                        $q->name('student')
                            ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                            ->field('id');
                    });
                });
            })
            ->with([
                'ksChengji' => function($query){
                    $query->field('id, kaohao_id, subject_id, defen, defenlv, bpaixu, xpaixu, qpaixu, bweizhi, xweizhi, qweizhi, biaozhunfen')
                        ->with([
                            'subjectName' => function ($q) {
                                $q->field('id, title, jiancheng, lieming');
                            }
                        ]);
                }
                ,'cjSchool' => function($query){
                    $query->field('id, jiancheng');
                }
                ,'cjStudent' => function($query){
                    $query->field('id, xingming, sex, shengri, xuehao');
                }
            ])
             ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->append(['banjiTitle', 'banTitle', 'xuehao'])
            ->select();
        return $data;
    }


    // 查询本次考试学生各学科成绩详细信息
    public function srcChengjiList($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'banji_id' => array()
            ,'auth' => [
                'check' => true
                ,'banji_id' => array()
            ]
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'asc'
            ,'all' => false
        );

        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $khlist = $this->search($src);
        if($khlist->isEmpty())
        {
            return $data = array();
        }

        $ksset = new \app\kaoshi\model\KaoshiSet;
        $xk = $ksset->srcSubject($src);

        $ks = new \app\kaoshi\model\Kaoshi;
        $ksInfo = $ks->kaoshiInfo($src['kaoshi_id']);
        $fg = new \app\teach\model\FenGong;
        $fgList = $fg->teacherFengong(session('user_id'), $ksInfo->getData("bfdate"));

        // 查询是否需要判断权限
        $user_id = session("user_id");
        $userInfo = \app\facade\OnLine::myInfo();
        if($userInfo->zhiwu_id == 10701 || $userInfo->zhiwu_id == 10703 || $userInfo->zhiwu_id == 10705 || $user_id <= 2)
        {
            $yzfg = true;
        } else {
            $yzfg = false;
        }

        $bzr = new \app\teach\model\BanZhuRen;
        $bzrAuth = $bzr->srcTeacherNow(session("user_id"));

        // 实例化学生数据模型
        $stu = new \app\student\model\Student;
        $data = array();
        foreach ($khlist as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['school_jiancheng'] = $value->cjSchool->jiancheng;
            if($value->cjStudent != Null){
                $data[$key]['student_xingming'] = $value->cjStudent->xingming;
                $data[$key]['sex'] = $value->cjStudent->sex;
            }else{
                $stuinfo = $stu::withTrashed()
                        ->where('id', $value->student_id)
                        ->field('id, xingming, sex')
                        ->find();
                if($stuinfo){
                    $data[$key]['student_xingming'] = $stuinfo->xingming;
                    $data[$key]['sex'] = $stuinfo->sex;
                }else{
                    $data[$key]['student_xingming'] = '被真删除';
                    $data[$key]['sex'] = '未知';
                }
            }

            $data[$key]['ban_title'] = $value->banjiTitle;
            $data[$key]['banji_title'] = $value->banTitle;
            $dfsum = 0;
            $sbjcnt = 0;

            if (!$value->ksChengji->isEmpty()) {
                $value->ksChengji = self::zzcj($value->ksChengji);
                foreach ($xk as $k => $val) {
                    if (isset($value->ksChengji[$val['lieming']]))
                    {
                        $temp_yzfg = $yzfg; # 如果是班主任则跳过验证
                        if ($yzfg == false && in_array($value->banji_id, $bzrAuth))
                        {
                            $temp_yzfg = true;
                        }
                        if ($temp_yzfg == false) {
                            if (isset($fgList[$value->banji_id][$val['id']]) && $fgList[$value->banji_id][$val['id']])
                            {
                                $data[$key][$val['lieming'] . 'defen'] = $value->ksChengji[$val['lieming']]->defen * 1;
                                $data[$key][$val['lieming'] . 'defenlv'] = $value->ksChengji[$val['lieming']]->defenlv * 1;
                                $data[$key][$val['lieming'] . 'bpaixu'] = $value->ksChengji[$val['lieming']]->bpaixu * 1;
                                $data[$key][$val['lieming'] . 'bweizhi'] = $value->ksChengji[$val['lieming']]->bweizhi;
                                $data[$key][$val['lieming'] . 'xpaixu'] = $value->ksChengji[$val['lieming']]->xpaixu * 1;
                                $data[$key][$val['lieming'] . 'xweizhi'] = $value->ksChengji[$val['lieming']]->xweizhi;
                                $data[$key][$val['lieming'] . 'qpaixu'] = $value->ksChengji[$val['lieming']]->qpaixu * 1;
                                $data[$key][$val['lieming'] . 'qweizhi'] = $value->ksChengji[$val['lieming']]->qweizhi;
                                $data[$key][$val['lieming'] . 'biaozhunfen'] = $value->ksChengji[$val['lieming']]->biaozhunfen;
                                $dfsum = $dfsum + $data[$key][$val['lieming'] . 'defen'] * 1 . '';
                                $sbjcnt++;
                            } else {
                                $data[$key][$val['lieming'] . 'defen'] = null;
                                $data[$key][$val['lieming'] . 'defenlv'] = null;
                                $data[$key][$val['lieming'] . 'bpaixu'] = null;
                                $data[$key][$val['lieming'] . 'bweizhi'] = null;
                                $data[$key][$val['lieming'] . 'xpaixu'] = null;
                                $data[$key][$val['lieming'] . 'xweizhi'] = null;
                                $data[$key][$val['lieming'] . 'qpaixu'] = null;
                                $data[$key][$val['lieming'] . 'qweizhi'] = null;
                                $data[$key][$val['lieming'] . 'biaozhunfen'] = null;
                            }
                        } else {
                            $data[$key][$val['lieming'] . 'defen'] = $value->ksChengji[$val['lieming']]->defen * 1;
                            $data[$key][$val['lieming'] . 'defenlv'] = $value->ksChengji[$val['lieming']]->defenlv * 1;
                            $data[$key][$val['lieming'] . 'bpaixu'] = $value->ksChengji[$val['lieming']]->bpaixu * 1;
                            $data[$key][$val['lieming'] . 'bweizhi'] = $value->ksChengji[$val['lieming']]->bweizhi;
                            $data[$key][$val['lieming'] . 'xpaixu'] = $value->ksChengji[$val['lieming']]->xpaixu * 1;
                            $data[$key][$val['lieming'] . 'xweizhi'] = $value->ksChengji[$val['lieming']]->xweizhi;
                            $data[$key][$val['lieming'] . 'qpaixu'] = $value->ksChengji[$val['lieming']]->qpaixu * 1;
                            $data[$key][$val['lieming'] . 'qweizhi'] = $value->ksChengji[$val['lieming']]->qweizhi;
                            $data[$key][$val['lieming'] . 'biaozhunfen'] = $value->ksChengji[$val['lieming']]->biaozhunfen;
                            $dfsum = $dfsum + $data[$key][$val['lieming'] . 'defen'] * 1 . '';
                            $sbjcnt++;
                        }
                    }else{
                        $data[$key][$val['lieming'] . 'defen'] = null;
                        $data[$key][$val['lieming'] . 'defenlv'] = null;
                        $data[$key][$val['lieming'] . 'bpaixu'] = null;
                        $data[$key][$val['lieming'] . 'bweizhi'] = null;
                        $data[$key][$val['lieming'] . 'xpaixu'] = null;
                        $data[$key][$val['lieming'] . 'xweizhi'] = null;
                        $data[$key][$val['lieming'] . 'qpaixu'] = null;
                        $data[$key][$val['lieming'] . 'qweizhi'] = null;
                        $data[$key][$val['lieming'] . 'biaozhunfen'] = null;
                    }
                }
            }else{
                foreach ($xk as $k => $val) {
                    $data[$key][$val['lieming'] . 'defen'] = null;
                    $data[$key][$val['lieming'] . 'defenlv'] = null;
                    $data[$key][$val['lieming'] . 'bpaixu'] = null;
                    $data[$key][$val['lieming'] . 'bweizhi'] = null;
                    $data[$key][$val['lieming'] . 'xpaixu'] = null;
                    $data[$key][$val['lieming'] . 'xweizhi'] = null;
                    $data[$key][$val['lieming'] . 'qpaixu'] = null;
                    $data[$key][$val['lieming'] . 'qweizhi'] = null;
                    $data[$key][$val['lieming'] . 'biaozhunfen'] = null;
                }
            }

            if ($sbjcnt > 0) {
                $data[$key]['sum'] = $dfsum;
                $data[$key]['avg'] = round($dfsum / $sbjcnt, 1);
            } else {
                $data[$key]['sum'] = null;
                $data[$key]['avg'] = null;
            }
        }

        return $data;
    }


    // 获取在线编辑成绩数据
    public function srcOnlineEdit($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'banji_id' => array()
            ,'subject_id' => array()
            ,'field' => 'student_xingming'
            ,'order' => 'desc'
            ,'page' => 1
            ,'limit' => 50
            ,'all' => false
        );
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $all = $src['all'];
        $src['all'] = true;
        $field = $src['field'];
        $order = $src['order'];
        unset($src['field'], $src['order']);
        $khlist = $this->searchLuruBase($src);

        if($khlist->isEmpty())
        {
            return $data = array();
        }
        $sbj = new \app\teach\model\Subject;
        $sbjList = $sbj->where('id', 'in', $src['subject_id'])
            ->field('id, title, jiancheng, lieming')
            ->select();
        $stu = new \app\student\model\Student;
        $data = array();
        foreach ($khlist as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['school_jiancheng'] = $value->cjSchool->jiancheng;
            $data[$key]['ban_title'] = $value->banTitle;
            if (!$value->cjStudent) {
                $stuInfo = $stu::withTrashed()
                    ->where('id', $value->student_id)
                    ->field('id, xingming, sex, shengri')
                    ->find();
                $data[$key]['student_xingming'] = $stuInfo->xingming;
                $data[$key]['sex'] = $stuInfo->sex;
                $data[$key]['shengri'] = $stuInfo->shengri;
            } else {
                $data[$key]['student_xingming'] = $value->cjStudent->xingming;
                $data[$key]['sex'] = $value->cjStudent->sex;
                $data[$key]['shengri'] = $value->cjStudent->shengri;
            }
            $data[$key]['xuehao'] = $value->xuehao;

            foreach ($sbjList as $sbj_k => $sbj_v) {
                $data[$key][$sbj_v->lieming] = '';
                foreach ($value->ksChengji as $cj_k => $cj_v) {
                    if($cj_v->subject_id == $sbj_v->id)
                    {
                        $data[$key][$sbj_v->lieming] = $cj_v->defen * 1;
                    }
                }
            }
        }

        $order == 'asc' ? $order = SORT_ASC : $order = SORT_DESC;
        $data = \app\facade\Tools::sortArrByManyField($data, $field, $order);

        if ($all != true) {
            $start = ($src['page'] - 1) * $src['limit'];
            $data = array_slice($data, $start, $src['limit']);
        }

        return $data;
    }


    // 查询本次考试各班级考号，并以班级进行分组
    public function srcBanjiKaohao($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'banji_id' => array()
        );
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['banji_id'] = str_to_array($src['banji_id']);

        // 获取考试标签
        $kh = new kh;
        $data = $kh->where('kaoshi_id', $src['kaoshi_id'])
            ->where('banji_id', 'in', $src['banji_id'])
            ->field(
            'banji_id
            ,school_id
            ,any_value(nianji) as nianji
            ,any_value(paixu) as paixu
            ,any_value(kaoshi_id) as kaoshi_id
            ,any_value(id) as id
            ,any_value(ruxuenian) as ruxuenian'
            )  ##这个地方需要改
            ->order(['banji_id'])
            ->group('banji_id, school_id')
            ->with([
                'cjBanji' => function($query){
                    $query->field('id, school_id, ruxuenian, paixu');
                }
                ,'cjSchool' => function($query){
                    $query->field('id, jiancheng');
                }
                ,'banjiKaohao' => function($query) use($src){
                    $query->field('id, student_id, banji_id')
                        ->where('kaoshi_id', $src['kaoshi_id'])
                        ->order(['banji_id', 'id'])
                        ->with([
                            'cjStudent' => function($q){
                                $q->field('id, xingming, sex, shoupin, xuehao');
                            }
                    ]);
                }
            ])
            ->append(['banjiTitle', 'numBanjiTitle'])
            ->select();

        $stu = new \app\student\model\Student;
        // 找出已经被删除学生，并添加该学生信息
        foreach ($data as $key => $value) {
            foreach ($value->banjiKaohao as $k => $val) {
                if($val->cjStudent == Null)
                {
                    $stuinof = $stu::onlyTrashed()
                        ->where('id', $val->student_id)
                        ->field('id, xingming, xuehao')
                        ->find();
                    if ($stuinof) {
                        $data[$key]->banjiKaohao[$key]->cjStudent = [
                            'id' => $stuinof->id
                            ,'xingming' => $stuinof->xingming
                            ,'sex' => $stuinof->sex
                            ,'shoupin' => $stuinof->shoupin
                            ,'xuehao' => $stuinof->xuehao
                        ];
                    }
                }
                $data[$key]['banjiKaohao'][$k]['shoupin'] = $val['cjStudent']['shoupin'];
            }
        }
        return $data;
    }


    // 查询各学科成绩精简版
    public function srcChengjiSubject($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'banji_id' => array()
            ,'searchval' => ''
            ,'auth' => [
                'check' => false
            ]
        );
        $src = array_cover($srcfrom, $src);
        // 查询成绩
        $khlist = $this->search($src);
        // 成绩整理
        if($khlist->isEmpty())
        {
            return $data = array();
        }
        $data = array();
        foreach ($khlist as $kh_k => $kh_val) {
            foreach ($kh_val->ksChengji as $cj_k => $cj_val) {
                $data[$cj_val->subject_id][$cj_val->id] = $cj_val->defen;
            }
        }

        return $data;
    }


    // 获取考试成绩最后更新时间
    public function lastUpdateTime($kaoshi_id)
    {
        // 获取考号
        $kh = new kh;
        $kaohaoids = $kh->where('kaoshi_id', $kaoshi_id)
            ->cache(true)
            ->column('id');

        $cj = new \app\chengji\model\Chengji;
        $lastcj = $cj->where('kaohao_id', 'in', $kaohaoids)
                    ->order(['update_time' => 'desc'])
                    ->find();

        return $lastcj;
    }


    // 转置成绩数组
    private function zzcj($array = array())
    {
        $arr = array();
        foreach ($array as $key => $value) {
            $array[$value->subjectName->lieming] = $value;
            unset($array[$key]);
        }
        return $array;
    }

}





