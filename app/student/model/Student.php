<?php

namespace app\student\model;

// 引用数据模型基类
use app\BaseModel;
// 引用班级数据模型类
use app\teach\model\Banji;

class Student extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'xingming' => 'varchar'
        ,'sex' => 'tinyint'
        ,'shengri' => 'int'
        ,'shenfenzhenghao' => 'varchar'
        ,'password' => 'varchar'
        ,'denglucishu' => 'int'
        ,'lastip' => 'varchar'
        ,'ip' =>  'varchar'
        ,'lasttime' => 'int'
        ,'thistime' => 'int'
        ,'banji_id' => 'int'
        ,'kaoshi' =>  'tinyint'
        ,'quanpin' => 'varchar'
        ,'shoupin' => 'varchar'
        ,'xuehao' =>  'varchar'
        ,'guoqi' => 'int'
        ,'status' => 'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        
    ];


    // 班级关联
    public function stuBanji()
    {
        return $this->belongsTo(\app\teach\model\Banji::class, 'banji_id', 'id');
    }


    // 年龄获取器
    public function getAgeAttr()
    {
    	$shengri = $this->getData('shengri');
        if(strlen($shengri) == 0 ){
            return '';
        };
        $age = \app\facade\Tools::fBirth($shengri, 1);
        return $age;
    }


    // 生日修改器
    public function setShengriAttr($value)
    {
        return strtotime($value);
    }


    // 生日获取器
    public function getShengriAttr($value)
    {
        return date('Y-m-d',$value);
    }


    // 性别获取器
    public function getSexAttr($value)
    {
        $sex = [
            '0' => '女'
            ,'1' => '男'
            ,'2' => '保密'
        ];

        $str = '';
        if(isset($sex[$value]))
        {
            $str = $sex[$value];
        }else{
            $str = '未知';
        }
        return $str;
    }


    // 性别获取器
    public function getKaoshiAttr($value)
    {
        $sex = [
            '0' => '不参加'
            ,'1' => '参加'
        ];

        $str = '';
        if(isset($sex[$value]))
        {
            $str = $sex[$value];
        }else{
            $str = '未知';
        }
        return $str;
    }


    // 数据筛选
    public function search($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'status' => ''
            ,'kaoshi' => ''
            ,'banji_id' => array()
            ,'auth' => [
                'check' => true
                ,'banji_id' => array()
            ]
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];

        $src = array_cover($srcfrom, $src);
        $src['banji_id'] = str_to_array($src['banji_id']);

        $njlist = \app\facade\Tools::nianJiNameList(time(), 'num');
        $zxnian = min($njlist); # 最近毕业年
        $banji = new \app\teach\model\Banji;
        $zx = $banji
            ->where('ruxuenian', '>=', $zxnian)
            ->column('id');

        $data = $this
                ->when($src['auth']['check'] === true, function ($query) use($src) {
                    $query->where('banji_id', 'in', $src['auth']['banji_id']);
                })
                ->when(count($src['banji_id']) > 0, function ($query) use($src) {
                    $query->where('banji_id', 'in', $src['banji_id']);
                })
                ->where('banji_id', 'in', $zx)
                ->when(strlen($src['searchval']) > 0, function($query) use($src){
                        $query
                            ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                            ->with([
                                'glSchool' => function($q){
                                    $q->field('id, title, jiancheng');
                                }
                            ])
                            ->field('id');
                })
                ->when(strlen($src['status']) > 0, function($query) use($src){
                        $query
                            ->where('status', $src['status'])
                            ->field('id');
                })
                ->when(strlen($src['kaoshi']) > 0, function($query) use($src){
                        $query
                            ->where('kaoshi', $src['kaoshi'])
                            ->field('id');
                })
                ->with([
                    'stuBanji'=>function($query){
                        $query
                            ->field('id, ruxuenian, paixu, alias, school_id')
                            ->with([
                                'glSchool' => function($query){
                                    $query->field('id, title, jiancheng');
                                },
                            ])
                            ->append(['banjiTitle']);
                    }
                ])
                ->field('id, xingming, sex, shengri, banji_id, kaoshi, xuehao, status, update_time')
                ->when($src['all'] == false, function ($query) use($src) {
                    $query
                        ->page($src['page'], $src['limit']);
                })
                ->order([$src['field'] => $src['order']])
                ->append(['age'])
                ->select();

        return $data;
    }


    // 数据筛选
    public function searchBy($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'status' => ''
            ,'kaoshi' => ''
            ,'banji_id' => array()
            ,'auth' => [
                'check' => true
                ,'banji_id' => array()
            ]
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];

        $src = array_cover($srcfrom, $src);
        $src['banji_id'] = str_to_array($src['banji_id']);

        $njlist = \app\facade\Tools::nianJiNameList(time(), 'num');
        $biyenian = min($njlist); # 最近毕业年
        $banji = new \app\teach\model\Banji;
        $by = $banji
            ->where('ruxuenian', '<', $biyenian)
            ->column('id');

        $data = $this
                ->when($src['auth']['check'] == true, function ($query) use($src) {
                    $query->where('banji_id', 'in', $src['auth']['banji_id']);
                })
                ->when(count($src['banji_id']) > 0, function ($query) use($src) {
                    $query->where('banji_id', 'in', $src['banji_id']);
                })
                ->where('banji_id', 'in', $by)
                ->when(strlen($src['searchval']) > 0, function($query) use($src){
                        $query
                            ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                            ->with([
                                'glSchool' => function($q){
                                    $q->field('id, title, jiancheng');
                                }
                            ])
                            ->field('id');
                })
                ->when(strlen($src['status']) > 0, function($query) use($src){
                        $query
                            ->where('status', $src['status'])
                            ->field('id');
                })
                ->when(strlen($src['kaoshi']) > 0, function($query) use($src){
                        $query
                            ->where('kaoshi', $src['kaoshi'])
                            ->field('id');
                })
                ->with([
                    'stuBanji'=>function($query){
                        $query
                            ->field('id, ruxuenian, paixu, alias, school_id')
                            ->with([
                                'glSchool' => function($query){
                                    $query->field('id, title, jiancheng');
                                },
                            ])
                            ->append(['banjiTitle']);
                    }
                ])
                ->field('id, xingming, sex, shengri, banji_id, kaoshi, status, update_time')
                ->when($src['all'] == false, function ($query) use($src) {
                    $query
                        ->page($src['page'], $src['limit']);
                })
                ->order([$src['field'] => $src['order']])
                ->append(['age'])
                ->select();

        return $data;
    }


    // 查询删除学生信息
    public function searchDel($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'auth' => [
                'check' => true
                ,'banji_id' => array()
            ]
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);

        $data = $this::onlyTrashed()
                ->order('update_time')
                ->when($src['auth']['check'] == true, function ($query) use($src) {
                    $query->where('banji_id', 'in', $src['auth']['banji_id']);
                })
                ->when(strlen($src['searchval']) > 0, function($query) use($src){
                        $query
                            ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                            ->field('id');
                })
                ->with([
                    'stuBanji'=>function($query){
                        $query
                            ->field('id, ruxuenian, paixu, school_id')
                            ->with([
                                'glSchool' => function($q) {
                                    $q->field('id, title, jiancheng');
                                }
                            ])
                            ->append(['banjiTitle']);
                    }
                ])
                ->field('id, xingming, sex, shengri, banji_id, status')
                ->when($src['all'] == false, function ($query) use($src) {
                    $query
                        ->page($src['page'], $src['limit']);
                })
                ->order([$src['field'] => $src['order']])
                ->append(['age'])
                ->select();

        return $data;
    }


    // 根据学生姓名、首拼、全拼搜索学生信息
    public function searchStr($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'banji_id' => ''
            ,'kaoshi' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);

        $data = $this->field('id, xingming, shengri, sex')
            ->whereOr('xingming|shoupin', 'like', '%' . $src['searchval'] . '%')
            ->when(strlen($src['banji_id']) > 0, function ($query) use ($src) {
                $query->where('banji_id', $src['banji_id']);
            })
            ->when(strlen($src['kaoshi']) > 0, function ($query) use ($src) {
                $query->where('kaoshi', $src['kaoshi']);
            })
            ->with(
                [
                    'stuBanji' => function ($query) {
                        $query->field('id, jiancheng')
                            ->with([
                                'glSchool'=>function($query){
                                    $query->field('id, title, jiancheng');
                                },
                            ]);
                    }
                ]
            )
            ->append(['age'])
            ->select();
        return $data;
}


    // 获取全部数据
    public function searchAll()
    {
        return $this->select();
    }


    // 同步表格数据
    public function tongBu($stuinfo, $school_id)
    {
        // 实例验证
        $validate = new \app\student\validate\Student;
        // 整理数据
        array_splice($stuinfo, 0, 4);       # 删除标题行
        $stuinfo = array_filter($stuinfo, function($q){ ## 过滤姓名、身份证号或班级为空的数据
            return $q[1] != null && strlen($q[3]) >0 && $q[4] != null;
        });

        // 获取班级信息
        $bj = array_column($stuinfo, 4);
        $arr = array_unique($bj);
        $bjStrName = array();     # 找不同班级
        $bjmod = new \app\teach\model\Banji;
        foreach ($arr as $key => $value) {
            $bjid = $bjmod->srcBanJiID($value, $school_id); # 获取班级ID
            if($bjid != false)
            {
                $bjStrName[$bjid] = $value;
            }
        }

        // 权限判断
        $bjqx = event('mybanji', array());
        $bjqx = $bjqx[0];
        if ($bjqx['check'] == true) {
            foreach ($bjStrName as $key => $value) {
                if (!in_array($key, $bjqx['banji_id'])) {
                    unset($bjStrName[$key]);
                }
            }
        }

        // 根据班级切割数组
        $arr = array();
        foreach ($bjStrName as $sbj_k => $sbj_val) {
            foreach ($stuinfo as $stu_k => $stu_val) {
                if($stu_val[4] == $sbj_val)
                {
                    $arr[$sbj_k][] = $stu_val;
                    unset($stuinfo[$stu_k]);
                }
            }
        }

        $delarr = array();      # 记录要删除学生ID
        $pinyin = new \Overtrue\Pinyin\Pinyin;
        // 循环班级数组，查询数据，进行对比，并返回结果。
        foreach ($arr as $key => $value) {
            $ruxuenian = $bjmod->where('id', $key)->value('ruxuenian');
            // 获取电子表格中身份证号
            $xlsStuList = array_column($value, 3);
            $xlsStuList = array_map('strtoupper', $xlsStuList);  # 将小写字母转换成大写字母
            $xlsStuList = array_map('trim', $xlsStuList);  # 过滤空格

            // 获取数据库中身份证号
            $serStulist = self::withTrashed()
                        ->where('banji_id',$bjid)
                        ->field('id, xingming, shenfenzhenghao, sex')
                        ->select();
            $sfzh = $serStulist->column('shenfenzhenghao');

            // 返回数据对比结果
            $jiaoji = array_intersect($xlsStuList, $sfzh);  #返回交集
            $add = array_diff($xlsStuList, $sfzh);
            $del = array_diff($sfzh, $xlsStuList);

            // 从新增的数据中查找是否有已经存在，但是班级不正确的信息。
            $add_temp = self::withTrashed()
                        ->where('shenfenzhenghao', 'in', $add)
                        ->field('shenfenzhenghao')
                        ->column('shenfenzhenghao', 'id');

            foreach ($add_temp as $add_temp_k => $add_temp_val) {
                // 这个地方在大小写不一致的时候容易出错，需要将小写转换成大写
                $k = array_search($add_temp_val, $add);
                $jiaoji[$k] = $add[$k];
                unset($add[$k]);
            }

            // 针对不同数据进行不同操作
            // 更新数据
            foreach ($jiaoji as $jj_k => $jj_val) {
                $oneStu = self::withTrashed()
                    ->where('shenfenzhenghao', $jj_val)
                    ->field('id, xingming, sex, xuehao, banji_id, quanpin, shoupin, shenfenzhenghao, kaoshi, delete_time')
                    ->find();
                if($oneStu->delete_time > 0)
                {
                    $oneStu->restore();
                }

                $quanpin = $pinyin->sentence($value[$jj_k][1]);
                $shoupin = $pinyin->abbr($value[$jj_k][1]);

                $upTemp = array();
                $upTemp = [
                    'id' => $oneStu->id
                    ,'xingming' => $value[$jj_k][1]
                    ,'sex' => $oneStu->getdata('sex')
                    ,'banji_id' => $key
                    ,'shenfenzhenghao' => $oneStu->shenfenzhenghao
                    ,'kaoshi' =>  $oneStu->getdata('kaoshi')
                    ,'xuehao' => $value[$jj_k][2]
                    ,'quanpin' => trim(strtolower(str_replace(' ', '', $quanpin)))
                    ,'shoupin' => trim(strtolower(str_replace(' ', '', $shoupin)))
                    ,'delete_time' => null
                ];

                $result = $validate->scene('edit')->check($upTemp);
                $msg = $validate->getError();
                if($result){
                    self::update($upTemp);
                }
            }

            // 新增数据
            $pinyin = new \Overtrue\Pinyin\Pinyin;
            $list = array();
            foreach ($add as $add_k => $add_val) {
                $sfzhval = strtoupper(trim($value[$add_k][3]));

                $quanpin = $pinyin->sentence($value[$add_k][1]);
                $shoupin = $pinyin->abbr($value[$add_k][1]);

                if(strlen($sfzhval) == 18)
                {
                    $shengri = substr($sfzhval, 6, 4) . '-' . substr($sfzhval, 10, 2) . '-' . substr($sfzhval, 12, 2);
                    if(is_numeric(substr($sfzhval,16,1))){
                        $sex = substr($sfzhval,16,1) % 2;
                    } else {
                        $sex = 2;
                    }
                }else{
                    $shengri = '1970-1-1';
                    $sex = 2;
                }

                $list[$add_k] = [
                    'xingming' => $value[$add_k][1]
                    ,'sex' => $sex
                    ,'shengri' => $shengri
                    ,'shenfenzhenghao' => $sfzhval
                    ,'ruxuenian' => $ruxuenian
                    ,'banji_id' => $key
                    ,'kaoshi' => 1
                    ,'xuehao' => $value[$add_k][2]
                    ,'quanpin' => trim(strtolower(str_replace(' ', '', $quanpin)))
                    ,'shoupin' => trim(strtolower(str_replace(' ', '', $shoupin)))
                    ,'guoqi' => \app\facade\Tools::mima_guoqi()
                ];

                $result = $validate->scene('create')->check($list[$add_k]);
                // dump($result);
                if(!$result){
                    unset($list[$add_k]);
                }
            }

            // 获取去掉重复数据的数组  
            $sfzh =  array_column($list,'shenfenzhenghao');
            $unique_arr = array_unique($sfzh);   
            // 获取重复数据的数组   
            $repeat_arr = array_diff_assoc($sfzh, $unique_arr);
            if(count($repeat_arr) > 0)
            {
                $data = "电子表中身份证号：";
                $i = 0;
                foreach ($repeat_arr as $repeat_k => $repeat_v) {
                    if($i == 0)
                    {
                        $data = $data . $repeat_v;
                    } else {
                        $data = $data . '、' . $repeat_v;
                    }
                    $i ++;
                }
                $data = $data . '重复。';
            }

            self::saveAll($list);

            // 删除数据
            foreach ($del as $del_k => $del_val) {
                $delarr[] = [
                    'id' => $serStulist[$del_k]->id
                    ,'banjiTitle' => $bjStrName[$key]
                    ,'xingming' => $serStulist[$del_k]->xingming
                    ,'sex' => $serStulist[$del_k]->sex
                ];
            }
        }

        return $delarr;
    }

}
