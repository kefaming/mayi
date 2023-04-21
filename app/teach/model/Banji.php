<?php

namespace app\teach\model;

// 引用数据模型基类
use app\BaseModel;

class Banji extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'school_id' => 'int'
        ,'ruxuenian' => 'int'
        ,'xueduan_id' => 'int'
        ,'paixu' => 'int'
        ,'alias' => 'varchar'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'status' => 'tinyint'
    ];



    // 查询所有班级
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'school_id' => ''
            ,'ruxuenian' => ''
            ,'status' => ''
            ,'auth' => [
                'check' => true
                ,'banji_id' => array()
            ]
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'paixu'
            ,'order' => 'asc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $src['school_id'] = str_to_array($src['school_id']);
        $src['ruxuenian'] = str_to_array($src['ruxuenian']);
        if(count($src['ruxuenian']) == 0)
        {
            $njname = $this->gradeName(time(),'num');     # 年级名对应表
            $src['ruxuenian'] = array_values($njname);
        }

        $sch = new \app\system\model\School;
        if ($src['field'] == 'paixu') {
            $src['orderSql'] = $this->classPaixu(time(), 'id', $src['order']);
        } else {
            $src['orderSql'] = $this->classPaixu(time(), 'id');
        }


        // 查询数据
        $data = $this
            ->when(count($src['school_id']) > 0, function($query) use($src){
                    $query->where('school_id', 'in', $src['school_id']);
                })
            ->when(count($src['ruxuenian']) > 0, function($query) use($src){
                    $query->where('ruxuenian', 'in', $src['ruxuenian']);
                })
            ->when(strlen($src['status']) > 0, function($query) use($src){
                    $query->where('status', $src['status']);
                })
            ->when($src['auth']['check'] != false, function($query) use($src){
                    $query->where('id', 'in', $src['auth']['banji_id']);
                })
            ->with(
                [
                    'glSchool' => function($query){
                        $query->field('id, title, jiancheng');
                    },
                ]
            )
            ->withCount([
                'glStudent'=>function($query){
                    $query->where('status', 1);
                }
            ])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->when($src['field'] == 'paixu', function ($query) use($src) {
                $query->order($src['orderSql'])
                    ->order([$src['field'] => $src['order']]);
            }, function ($query) use($src) {
                $query->order([$src['field'] => $src['order']]);
            })
            ->append(['banjiTitle', 'banTitle', 'grade', 'bzr'])
            ->select();

        return $data;
    }


    // 以年级分组查询班级
    public function searchNjGroup($srcfrom)
    {
        // 整理变量
        $src = [
            'school_id' => ''
            ,'ruxuenian' => ''
            ,'status' => '1'
        ];
        $src = array_cover($srcfrom, $src) ;
        $src['ruxuenian'] = str_to_array($src['ruxuenian']);

        // 查询年级数据
        $data = self:: where('school_id', $src['school_id'])
        ->where('ruxuenian', 'in', $src['ruxuenian'])
        ->where('status', $src['status'])
        ->distinct(true)
        ->field('ruxuenian')
        ->with([
            'njBanji'=>function($query)use($src){
                $query->where('status',1)
                ->where('school_id', $src['school_id'])
                ->field('id, ruxuenian, paixu')
                ->where('status', 1)
                ->order('paixu')
                ->append(['banjiTitle', 'banTitle']);
            }
        ])
        ->select();

        return $data;
    }


    // 班级排序
    # ziduan 生成后的字段名称
    public function classPaixu($riqi = 0, $ziduan = 'banji_id', $order = 'asc')
    {
        $sch = new \app\system\model\School;
        $orderSql = $sch->schPaixu('school_id');;
        $njList = $this->gradeName($riqi,'num');
        $bjList = $this->where('ruxuenian', 'in', $njList)
            ->order($orderSql)
            ->order(['ruxuenian' => 'desc'])
            ->order(['paixu' => $order])
            ->column(['id']);
        $paixuList = implode('\', \'', $bjList);
        $paixuList = "field(" . $ziduan . ", '". $paixuList. "')";
        $orderSql = \think\facade\Db::raw($paixuList);
        return $orderSql;
    }


    // 学校关联模型
    public function glSchool(){
        return $this->belongsTo(\app\system\model\School::class, 'school_id', 'id');
    }


    // 学校关联模型
    public function glStudent(){
        return $this->hasMany(\app\student\model\Student::class, 'banji_id', 'id')
            ->hidden(['password', 'shenfenzhenghao']);;
    }


    // 年级-班级关联表
    public function njBanji()
    {
        return $this->hasMany('Banji', 'ruxuenian', 'ruxuenian');
    }


    // 年级-班级关联表
    public function glBanZhuRen()
    {
        return $this->hasMany(\app\teach\model\BanZhuRen::class, 'banji_id', 'id')
            ->where('bfdate', '<=', time());
    }


    // 班级名获取器
    public function getNumTitleAttr()
    {
    	// 获取基础信息
        $njname = $this->gradeName(time(), 'str');     # 年级名对应表
    	$nj = $this->getAttr('ruxuenian');
    	$bj = $this->getAttr('paixu');
        $numnj = array_flip(array_keys($njname));

        if(array_key_exists($nj, $numnj))
        {
            $numname = ($numnj[$nj] + 1) . '.' . $bj;
        }else{
            $numname = $nj . '.' . $bj;
        }

    	return $numname;
    }


    // 班级名获取器
    public function getBanjiTitleAttr()
    {
        $title = self::getGradeAttr() . self::getBanTitleAttr();
        return $title;
    }


    // 班名获取器
    public function getBanTitleAttr()
    {

        $alias = \app\facade\System::sysInfo();
        if($alias->classalias === 1)
        {
            $title = $this->alias;
            if($title == '')
            {
                $bj = $this->getAttr('paixu');
                $title = self::numToWord($bj) . '班';
                $del = $this->getAttr('delete_time');
                $del == null ?  $title : $title = $title & '(删)' ;
            }else{
                $title = $title . '班';
            }
        }else{
            $bj = $this->getAttr('paixu');
            $title = self::numToWord($bj) . '班';
            $del = $this->getAttr('delete_time');
            $del == null ?  $title : $title = $title & '(删)' ;
        }

        return $title;
    }


    // 年级名获取器
    public function getGradeAttr()
    {
        $njList = $this->gradeName(time());
        if(isset($njList[$this->getAttr('ruxuenian')]))
        {
            $title = $njList[$this->getAttr('ruxuenian')];
        }else{
            $title = $this->getAttr('ruxuenian') . '界';
        }
        return $title;
    }


    // 班主任获取器
    public function getBzrAttr()
    {
        $bzrList = $this->glBanZhuRen
            ->order('bfdate', 'desc')
            ->slice(0,1);
        $str = '';

        if (isset($bzrList[0]))
        {
            if($this->getBiyeAttr !== true)
            {
                if($bzrList[0]->glAdmin)
                {
                    $str = $bzrList[0]->glAdmin->xingming;
                }
            }
        }

        return $str;
    }


    // 判断当前班级是否已经毕业
    public function getBiyeAttr()
    {
        $sys = \app\facade\System::sysInfo();
        $cnt = count(explode('|', $sys->gradelist));
        $ruxuenian = $this->ruxuenian;
        $date_r = strtotime($ruxuenian + $cnt . '-8-1');
        $str = '';
        if(time() > $date_r)
        {
            $str = true;
        }else{
            $str = false;
        }
        return $str;
    }


    /**
    * 将“一年级一班”格式的班级名转换成入学年和班级排序
    * $str是原文件格式
    * 返回班级ID
    */
    public function srcBanJiID($str, $school_id)
    {
        $str = trim($str);
        // 获取年级、班级列表
        $njlist = $this->gradeName(time(),'str');
        $bjlist = $this->className();

        $nj = '';
        $bj = '';
        // 获取年级
        foreach ($njlist as $key => $value) {
            $nj = stripos($str, $value, 0);  # 查找年级第一次出现。
            if($nj === 0)
            {
                $nj = $key;
                break;
            }
        }

         // 去掉年级，获取班级
        $str = str_replace($njlist[$nj], '', $str);
        // 获取班级
        $bj = -1;
        foreach ($bjlist as $key => $value) {
            if ($value === $str) {
                $bj = $key;
                break;
            }
        }

        $alias = \app\facade\System::sysInfo();

        if($nj > 0 && ($bj > 0 || $bj == -1))
        {
            // 查询班级id
            $banji = new \app\teach\model\Banji;
            $id = $banji->where('ruxuenian', $nj)
                    ->when($alias->classalias === 1, function ($query) use($str, $bj) {
                        $query
                            ->when($bj === -1, function ($q) use($str) {
                                $q
                                    ->where('alias', substr_replace($str, '', strlen($str) - 3));
                            }, function ($q) use($bj) {
                                $q
                                    ->where('paixu', $bj);
                            });
                    }, function ($query) use($bj) {
                        $query->where('paixu', $bj);
                    })
                    ->where('ruxuenian', $nj)
                    ->where('school_id', $school_id)
                    ->value('id');
        }else {
            $id = '';
        }

        return $id;
    }


    /**
    * 格式化班级名称
    * $grade  入学年
    * $paixu   班级排序
    * $category 是否返回汉字
    * 返回班级ID
    */
    public function fClassTitle($grade = 1950, $jiedian = 0, $paixu = 1, $category = true)
    {
        //获取班级、年级列表
        $njlist = $this->gradeName($jiedian,'str');
        $title = '';
        if( isset($njlist[$grade]) )
        {
            switch ($category) {
                case true:
                    $title = $njlist[$grade] . self::numToWord($paixu) . '班';
                    break;
                default:
                    $njlist = array_flip(array_keys($njlist));
                    $title = $njlist[$grade].''.$paixu;
                    break;
            }
        }else{
            switch ($category) {
                case true:
                    $title = $grade . '界' . self::numToWord($paixu) . '班';
                    break;
                default:
                    $njlist = array_flip(array_keys($njlist));
                    $title = $njlist[$grade].'.'.$paixu;
                    break;
            }
        }

        return $title;
    }


    // 生成年级名
    public function gradeName($riqi, $category = 'str')
    {
        //  转换日期格式
        if(!is_numeric($riqi))
        {
            $riqi = strtotime($riqi);
        }

        // 定义学年时间节点日期为每年的8月1日
        // $yd = '8-1';
        $sysClass = \app\facade\System::sysInfo();
        $jd = date('-m-d', $sysClass->getData('xuenian'));

        if($riqi == 0 || $riqi == '')
        {
            $jiedian = strtotime(date('Y') . $jd);
            $thisday = time();
            $nian = date('Y');
        }else{
            $jiedian = strtotime(date('Y', $riqi) . $jd);
            $thisday = $riqi;
            $nian = date('Y', $riqi);
        }

        $thisday <= $jiedian ? $str = 1 : $str = 0;
        $nian = $nian - $str;

        // 获取年级最大数
        $gradelist = explode('|', $sysClass->gradelist);
        $gradeMax = count($gradelist);

        $njlist = array();
        if($category != 'str')
        {
            for($i = 0; $i < $gradeMax; $i ++)
            {
                $njlist[$gradelist[$i]] = $nian - $i;
            }
        }else{
            for($i = 0; $i < $gradeMax; $i ++)
            {
                $njlist[$nian - $i] = $gradelist[$i];
            }
        }
        return $njlist;
    }


    // 班级列表
    public function className()
    {

        $sys = \app\facade\System::sysInfo();
        $classmax = $sys->classmax;

        $bjarr = array();
        for($i = 1; $i <= $classmax; $i++)
        {
            $bjarr[$i] = self::numToWord($i) . '班';
        }
        return $bjarr;
    }


    public function numToWord($num)
    {
        if(!preg_match("/^[1-9][0-9]*$/", $num))
        {
            return '';
        }
        $chiUni = array('', '万', '亿', '万');
        $cnt = strlen($num);  #取数字长度
        $num = (string)$num;
        $chiStr = '';

        //分割数组
        $x = 0;
        $y = 0;
        for ($i = $cnt - 1; $i >= 0; $i --) {
            if($x < 4)
            {
                $arr[$y][$x] = $num[$i];
            }else{
                $x = 0;
                $y = $y + 1;
                $arr[$y][$x] = $num[$i];
            }
            $x = $x + 1;
        }

        foreach ($arr as $key => $value) {
            // 判断有几位数
            $cnt = count($value);
            $thisStr = self::numTo($value, $key);

            if($thisStr == '零')
            {
                $chiStr = self::numTo($value, $key) . $chiStr;
            }else{
                $chiStr = self::numTo($value, $key) . $chiUni[$key] . $chiStr;
            }
        }

        $chiStr = str_replace("零零", "零", $chiStr);
        return $chiStr;
    }


    private function numTo($arr = array(), $key = 0)
    {
        $cnt = count($arr);
        $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
        $chiUni = array('','十', '百', '千');
        $chiStr = '';
        $zero = true;  # 上一个数字是否为0
        $key = $key * 4;
        if($cnt == 0 || array_sum($arr) == 0)
        {
            return $chiNum[0];
        }
        switch ($cnt) {
            case 2:
                $temp = $arr[1];
                if($temp == 1)
                {
                    $chiStr = $chiUni[1];
                }else{
                    $chiStr = $chiNum[$temp] . $chiUni[1];
                }
                $temp = $arr[0];
                $temp != 0 ? $chiStr = $chiStr . $chiNum[$temp] : '' ;
                break;
            default:
                for($i = 0; $i<$cnt; $i++)
                {
                    $temp = $arr[$i];
                    if($temp == 0)
                    {
                        if($zero != true)
                        {
                            $chiStr = $chiNum[$temp] .$chiStr;
                        }
                        $zero = true;
                    }else{
                        $chiStr = $chiNum[$temp] . $chiUni[$i] .$chiStr;
                        $zero = false;
                    }
                }
                break;
        }
        return $chiStr;
    }

}
