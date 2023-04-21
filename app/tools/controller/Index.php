<?php
declare (strict_types = 1);

namespace app\tools\controller;

// 引用控制器基类
use app\BaseController;

class Index extends BaseController
{

    // 给数组按多条件排序
    public function sortArrByManyField(){
        $args = func_get_args();
        if(empty($args)){
            return null;
        }
        $arr = array_shift($args);
        if(!is_array($arr)){
            throw new Exception("第一个参数不为数组");
        }

        foreach($args as $key => $field){
            if(is_string($field)){
                $temp = array();
                foreach($arr as $index => $val){
                    $temp[$index] = $val[$field];
                }
                $args[$key] = $temp;
            }
        }

        $args[] = &$arr;//引用值
        $keys = array_keys($args[0]);
        call_user_func_array('array_multisort', $args);

        return array_pop($args);
    }


    /**
     * 根据键值，用数组2的值替换数组1的值
     * $cover 覆盖数组，存储新值的数组
     * $covered 被覆盖数组，被更改值的数组
     * 返回 新arr1
     * */
    public function array_cover($cover = array(), $covered = array())
    {
        foreach ($cover as $key => $value) {
            if($value === "" || $value === null)
            {
                unset($cover[$key]);
            }
        }
        if(is_array($cover) && is_array($covered))
        {
            foreach ($cover as $key => $value) {
                if(isset($covered[$key]) == true)
                {
                    $covered[$key] = $cover[$key];
                }
            }
        }
        return $covered;
    }


    /**
    * 把request到的参数转换成数组，并删除空值
    *
    * @access public
    * @param str或array $str 表单中获取的参数
    * @return array 返回类型
    */
    public function str_to_array($str = array())
    {
        // 如果str是字符串，则转换成数组
        if(is_array($str) == false)
        {
            if ($str != '' || $str != null) {
                $str = (string)$str;
                $str = explode(',', $str);
                // 循环数组，删除空元素
                foreach ($str as $key => $value) {
                    if($value == "" && $value == null){
                        unset($str[$key]);
                    }
                }
            }else{
                $str = array();
            }
        }else{
            $str = $str;
        }

        return $str;
    }


    /**
    * 把重新整理从数据模型中返回的对象
    * @access public
    * @param str或array $str 表单中获取的参数
    * @return array 返回类型
    */
    public function reset_data($data = array(), $cnt)
    {
        $arr = [
            'code' => 0  // ajax请求次数，作为标识符
            ,'msg' => ""  // 获取到的结果数(每页显示数量)
            ,'count' => 0 // 符合条件的总数据量
            ,'data' => '' //获取到的数据结果
        ];

        // 整理数据
        if($cnt > 0)
        {
            $arr = [
                'code' => 0  // ajax请求次数，作为标识符
                ,'msg' => ""  // 获取到的结果数(每页显示数量)
                ,'count' => $cnt // 符合条件的总数据量
                ,'data' => $data //获取到的数据结果
            ];
        }
        return $arr;
    }



        /**
    * 把重新整理从数据模型中返回的对象
    * @access public
    * @param str或array $str 表单中获取的参数
    * @return array 返回类型
    */
    public function reset_array($arr = array(), $srcfrom)
    {
        // 整理变量
        $src = [
            'field' => ''
            ,'order' => 'desc'
            ,'page' => '1'
            ,'limit' => '10'
        ];
        $src = array_cover($srcfrom, $src);
        $data = [   # 数据合并
            'code' => 0 , # ajax请求次数，作为标识符
            'msg' => "",  # 获取到的结果数(每页显示数量)
            'count' => 0, # 符合条件的总数据量
            'data' => [], # 获取到的数据结果
        ];


        // 重新整理数据
        $cnt = count($arr);    # 记录总数
        if($cnt > 0){
            if($src['field'] != '')
            {
                $src['order'] == 'desc' ? $src['order'] = SORT_DESC
                : $src['order'] = SORT_ASC;   # 数据排序
                $arr = $this->sortArrByManyField($arr, $src['field'], $src['order']);
            }
            if($src['page'] > 0 && $src['limit'] > 0)
            {
                $limit_start = $src['page'] * $src['limit'] - $src['limit']; # 获取当前页数据
                $limit_length = $src['limit'];
                $arr = array_slice($arr, $limit_start, $limit_length);
            }

            $data = [   # 数据合并
                'code' => 0 , # ajax请求次数，作为标识符
                'msg' => "",  # 获取到的结果数(每页显示数量)
                'count' => $cnt, # 符合条件的总数据量
                'data' => $arr, # 获取到的数据结果
            ];
        }
        return $data;
    }




    // 加密
    public function encrypt($data, $key)
    {
        $key    =    md5($key);
        $x        =    0;
        $len    =    strlen($data);
        $l        =    strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= $key[$x];
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            $str .= chr(ord($data[$i]) + (ord($char[$i])) % 256);
        }
        return base64_encode($str);
    }


    //  解密
    public function decrypt($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
            {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else
            {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }


    /**
    * $date是时间戳
    * $type为1的时候是虚岁,2的时候是周岁
    */
    public function fBirth($date = 0, $type = 1){
        $nowYear = date("Y", time());
        $nowMonth = date("m", time());
        $nowDay = date("d", time());
        $birthYear = date("Y", $date);
        $birthMonth = date("m", $date);
        $birthDay = date("d", $date);
        if($type == 1){
            $age = $nowYear - ($birthYear - 1);
        }elseif($type == 2){
            if($nowMonth < $birthMonth){
                $age = $nowYear - $birthYear - 1;
            }elseif($nowMonth == $birthMonth){
                if($nowDay < $birthDay){
                    $age = $nowYear - $birthYear - 1;
                }else{
                    $age = $nowYear - $birthYear;
                }
            }else{
                $age = $nowYear - $birthYear;
            }
        }
       return $age;
    }


    // 随机生成字符串
    //取随机10位字符串
    public function sjStr($len = 4)
    {
        $strs="1234567890qwertyuiopasdfghjklzxcvbnm";
        $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),$len);
        return $name;
    }


    // 根据时间获取年级列表,$value='str'时，键为2020，值为一年级,否则反过来
    public function nianJiNameList($riqi = 0, $value = 'str')
    {
        // 实例化年级控制器
        $bj = new \app\teach\model\Banji;
        $njList= $bj->gradeName($riqi, $value);
        return $njList;
    }


    // 根据成绩算排序和位置,在统计学生各学科成绩总分排序中使用
    public function paiwei($cj, $cjkey)
    {
        $rank = 0;
        $last = '-a';
        $i = 0;
        $cnt = count($cj);
        $cjkey = $cjkey * 1;
        if($cnt <= 0)
        {
            $data = [
                'rank' => 0
                ,'weizhi' => 0
            ];
        } else if($cnt == 1) {
            $data = [
                'rank' => 1
                ,'weizhi' => 100
            ];
        }else{
            arsort($cj);
            foreach ($cj as $k => $value) {
                $i ++;
                if($last != $value)
                {
                    $rank = $i;
                }
                if($k == $cjkey)
                {
                    break;
                }
                $last = $value;
            }
            $data = [
                'rank' => $rank
                ,'weizhi' => round(($cnt - $rank) / ($cnt - 1) * 100, 0)
            ];
        }
        return $data;
    }


    public function mima_guoqi() {
        // 计算帐号禁用时间
        $jyshijian = config('shangma.mimaguoqi');
        $now = date("Y-m-d", time());
        $jinyong = strtotime("$now+".$jyshijian." day");
        return $jinyong;
    }

}
