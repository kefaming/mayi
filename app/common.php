<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

	// 班级列表
	function banji_name_list()
	{
		// 实例化年级控制器
        $bj = new \app\teach\model\Banji;
        $njList= $bj->banJiNamelist();
        return $njList;
	}

    // 查询统计项目
    function src_tjxm($category_id)
    {
        $tjxm = new \app\kaoshi\model\TongjiXiangmu;
        $data = $tjxm->srcTongji($category_id);
        return $data;
    }


    // 可以组织考试的单位列表
    function kaoshi_school_list()
    {
        // 实例化单位模型
        $sch = new \app\system\model\School;
        $schlist = $sch->kaoshi();
        return $schlist;
    }


	// 整理教师名,数组转字符串
	function teacher_names($list = array())
	{
		if (count($list) == 0) {
            return '';
        }

		$names = '';
		foreach ($list as $key => $value) {
			if($key == 0)
			{
				$names = $value['teacher']['xingming'];
			}else{
				$names = $names . '、'. $value['teacher']['xingming'];
			}
		}

		return $names;
	}


    /*
     * 根据键值，用数组2的值替换数组1的值
     * $cover 覆盖数组，存储新值的数组
     * $covered 被覆盖数组，被更改值的数组
     * 返回 新arr1
     */
    function array_cover($cover = array(), $covered = array())
    {
        $arr = \app\facade\Tools::array_cover($cover, $covered);
    	return $arr;
    }


    /**
    * 把request到的参数转换成数组，并删除空值
    *
    * @access public
    * @param str或array $str 表单中获取的参数
    * @return array 返回类型
    */
    function str_to_array($str)
    {
        $data = \app\facade\Tools::str_to_array($str);
        return $data;
    }


    /**
    * 把重新整理从数据模型中返回的对象
    * @access public
    * @param str或array $str 表单中获取的参数
    * @return array 返回类型
    */
    function reset_data($data, $cnt)
    {
        $data = \app\facade\Tools::reset_data($data, $cnt);
        return $data;
    }


    // 定义EXCEL列名
    function excel_column_name()
    {
        $liemingarr = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW'];
        return $liemingarr;
    }


    // 已知密码进行验证
    function login_check($inputPassword, $serverPassword)
    {
        // 实例化加密类
        $md5 = new \WhiteHat101\Crypt\APR1_MD5;
        //验证密码
        $check = $md5->check($inputPassword, $serverPassword);
        return $check;
    }


    function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

