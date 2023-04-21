<?php

namespace app\rongyu\model;

// 引用数据模型基类
use app\BaseModel;

// 引用荣誉信息数据模型
use app\rongyu\model\JsRongyuInfo as info;

/**
 * @mixin \think\Model
 */
class Tongji extends BaseModel
{
    
    // 获取全区各级特别是立项数
    public function quGeji($srcfrom)
    {
        // 获取级别ID
        $category = new \app\system\model\Category;
        $categoryList = $category->srcBetweenID(10204, 10208);
        // 查询课题
        $info = new info();
        $list = $info->searchRySj($srcfrom);

        // 创建统计结果数组
        $tongji = array();
        foreach ($categoryList as $key => $value) {
            $tongji[$value->id] = 0;
        }
        // 统计荣誉数
        foreach ($list as $key => $value) {
            if(isset($value->ryTuce->fzSchool))
            {
                if(isset($tongji[$value->ryTuce->fzSchool->jibie_id])){
                    $tongji[$value->ryTuce->fzSchool->jibie_id]++;
                }
            }
        }

        $data = [
            'xAxis'=>array('总课教师荣誉数')
            ,'fuzhu'=>array(0)
            ,'data'=>array(array_sum($tongji))
            ,'dataname'=>'教师荣誉数'
        ];
        foreach ($categoryList as $key => $value) {
            $data['xAxis'][] = $value->title;
        }

        $fuzhu = 0;
        foreach ($tongji as $key => $value) {
            $data['fuzhu'][] = $fuzhu;
            $fuzhu = $fuzhu + $value;
            $data['data'][] = $value;
        }
        return $data;
    }


    // 获取全区各级特别是立项数
    public function quGeDanwei($srcfrom)
    {
        
        // 获取级别ID
        $school = new \app\system\model\School;
        $src = [
            'low' => '校级'
            ,'high' => '区级'
            ,'order' => 'asc'
        ];
        $schoolList = $school->srcJibie($src)->column('jiancheng','id');
        $schoolList['qt'] = '其它';
        // 获取级别ID
        $category = new \app\system\model\Category;
        $categoryList = $category->srcBetweenID(10204, 10208)->column('title','id');
        $categoryList['qt'] = '其它';
        // 查询课题
        $info = new info();
        $list = $info->searchRySj($srcfrom);

        // 创建统计结果数组
        $tongji = array();
        foreach ($schoolList as $key => $value) {
            foreach ($categoryList as $k => $val) {
                $tongji[$key]['name'] = $value;
                $tongji[$key][$k] = 0;
            }
        }

        // 循环计算已知级别和负责单位的课题
        foreach ($list as $key => $value) {
            if(isset($value->hjschool_id))
            {
                if(isset($value->ryTuce->fzSchool->jibie_id)){
                    $tongji[$value->hjschool_id][$value->ryTuce->fzSchool->jibie_id] ++;
                }else{
                    $tongji[$value->hjschool_id]['qt'] ++;
                }
            }else{
                if(isset($value->ryTuce->fzSchool->jibie_id)){
                    $tongji['qt'][$value->ryTuce->fzSchool->jibie_id] ++;
                }else{
                    $tongji['qt']['qt'] ++;
                }
            }
        }

        $tjdata = array();
        $x = 0;
        foreach ($tongji as $key => $value) {
            foreach ($value as $k => $val) {
                $tjdata[$x][] = $val;
            }
            $x++;
        }

        $series = array();
        foreach ($categoryList as $catk => $catval) {
            $series[] = [
                'name' => $catval
                ,'type' => 'bar'
                ,'stack' => 'total'
                ,'label' => [
                    'show' => true
                ]
                ,'emphasis' => [
                    'focus' => 'series'
                ]
            ];
        }

        // 结果赋值
        $data = [
            'legend' => array_values($categoryList)
            ,'xAxis' => [
                'type' => 'value'
                ,'data' => ''
            ]
            ,'yAxis' => [
                'type' => 'category'
                ,'data' => array_values($schoolList)
            ]
            ,'series' => $series
            ,'data' => $tjdata
        ];

        return $data;
    }

}
