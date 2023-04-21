<?php

namespace app\chengji\model;

// 引用基类
use \app\BaseModel;

class Tongji extends BaseModel
{

    // 统计成绩
    public function tongjiCnt($cj = array(), $subject_id)
    {
        $data = array();
        // 循环统计各学科成绩
        foreach ($subject_id as $key => $value) {
            $cjcol = array_column($cj, $value['lieming'] . 'defen');

            $cjcol = array_filter($cjcol, function($item){
                return $item != null;
            });
            $temp = array();
            $temp['xkcnt'] = count($cjcol);
            $data[$value['lieming']] = $temp;
        }
        $data['stucnt'] = count($cj);
        return $data;
    }


    // 统计成绩
    public function tongjiSubject($cj = array(), $subject)
    {
        // 设置默认值
        $tempNull = [
            'chengji_cnt' => 0
            ,'sum' => null
            ,'biaozhuncha' => null
            ,'avg' => null
            ,'youxiu' => null
            ,'youxiulv' => null
            ,'jige' => null
            ,'jigelv' => null
            ,'sifenwei' => [
                '0' => null
                ,'1' => null
                ,'2' => null
            ]
            ,'q1' => 0
            ,'q2' => 0
            ,'q3' => 0
            ,'max' => null
            ,'min' => null
            ,'zhongshu' => null
            ,'zhongweishu' => null
            ,'defenlv' => null
            ,'chashenglv' => 0
            ,'canshilv' => 0
        ];

        $data = array();
        $sbjdefencnt = 0;   #记录试卷满分

        // 循环统计各学科成绩
        foreach ($subject as $key => $value) {
            $cjcol = array();
            $sbjdefencnt = $sbjdefencnt + $value['fenshuxian']['manfen'];
            foreach ($cj as $cj_k => $cj_v) {
                $cjcol[] = $cj_v[$value['lieming'] . 'defen'];
            }
            $stu_cnt = count($cjcol);
            $cjcol = array_filter($cjcol, function($item){
                return $item !== null;
            });

            $temp = $tempNull;
            $temp['id'] = $value['id'];
            $temp['stu_cnt'] = $stu_cnt;

            if($cjcol != null) {
                $temp['chengji_cnt'] = count($cjcol);
                $temp['sum'] = array_sum($cjcol);
                if ($temp['chengji_cnt'] > 0) {
                    $temp['avg'] = $temp['sum'] / $temp['chengji_cnt'];
                    $temp['defenlv'] = $temp['avg'] / $value['fenshuxian']['manfen'];
                }
                $temp['biaozhuncha'] = round($this->getVariance($temp['avg'], $cjcol, true), 2);
                $temp['avg'] = round($temp['avg'], 2);
                $temp['youxiu'] = $this->rate($cjcol, $value['fenshuxian']['youxiu']);
                $temp['youxiulv'] = round($temp['youxiu'] / $temp['chengji_cnt'] * 100, 2);
                $temp['jige'] = $this->rate($cjcol, $value['fenshuxian']['jige']);
                $temp['jigelv'] = round($temp['jige'] / $temp['chengji_cnt'] * 100, 2);
                $temp['max'] = max($cjcol);
                $temp['min'] = min($cjcol);
                $temp['sifenwei'] = $this->myquartile($cjcol);
                $temp['q1'] = $temp['sifenwei'][0];
                $temp['q2'] = $temp['sifenwei'][1];
                $temp['q3'] = $temp['sifenwei'][2];
                $temp['zhongshu'] = $this->zhongshu($cjcol);
                $temp['zhongweishu'] = $this->zhongweishu($cjcol);
                $temp['defenlv'] = round($temp['defenlv'] * 100, 2);
                $temp['canshilv'] = round($temp['chengji_cnt'] / $temp['stu_cnt'] * 100, 2);
                $temp['chashenglv'] = round(($temp['chengji_cnt'] - $temp['jige']) / $temp['chengji_cnt'] * 100, 2);
            }
            $data['cj'][$value['lieming']] = $temp;
        }

        $temp = $tempNull;
        $cjcol = array_column($cj, 'sum');
        $stu_cnt = count($cjcol);
        $cjcol = array_filter($cjcol, function ($item) {
                return $item !== null;
            });
        $temp['id'] = 0;
        $temp['stu_cnt'] = $stu_cnt;
        if ($cjcol != null) {
            $temp['chengji_cnt'] = count($cjcol);   # 报名人数
            $temp['sum'] = array_sum($cjcol);
            if( $temp['chengji_cnt'] > 0) {
                $temp['avg'] = $temp['sum'] / $temp['chengji_cnt'];
                $temp['defenlv'] = $temp['avg'] / $sbjdefencnt;
            }
            $temp['biaozhuncha'] = round($this->getVariance($temp['avg'], $cjcol, true), 2);
            $temp['avg'] = round($temp['avg'], 2);
            $temp['max'] = max($cjcol);
            $temp['min'] = min($cjcol);
            $temp['youxiu'] = $this->rateAll($cj, $subject, 'youxiu');
            $temp['jige'] = $this->rateAll($cj, $subject, 'jige'); #全科及格率
            $temp['sifenwei'] = $this->myquartile($cjcol);
            $temp['q1'] = $temp['sifenwei'][0];
            $temp['q2'] = $temp['sifenwei'][1];
            $temp['q3'] = $temp['sifenwei'][2];
            $temp['zhongshu'] = $this->zhongshu($cjcol);
            $temp['zhongweishu'] = $this->zhongweishu($cjcol);
            $temp['defenlv'] = round($temp['defenlv'] * 100, 2);
            $temp['youxiulv'] = round($temp['youxiu'] / $temp['chengji_cnt'] * 100, 2);
            $temp['jigelv'] = round($temp['jige'] / $temp['chengji_cnt'] * 100, 2);
            $temp['canshilv'] = round($temp['chengji_cnt'] / $temp['stu_cnt'] * 100, 2);
            $temp['chashenglv'] = round(($temp['chengji_cnt'] - $temp['jige']) / $temp['chengji_cnt'] * 100, 2);
        }
        $data['cj']['all'] = $temp;

        return $data;
    }


    // 统计优秀、及格率人数
    public function rate($cj = array(), $fenshuxian)
    {
        $cnt = 0;
        foreach ($cj as $key => $value) {
            if($value >= $fenshuxian) {
                $cnt ++;
            }
        }
        return $cnt;
    }


    // 全科及格率
    public function rateAll($cj = array(), $sbj = array(), $category = 'jige')
    {
        $jige = 0;  # 总及格人数
        $row = 0; #记录有成绩的学生数
        $sbjcnt = count($sbj);
        // 开始循环每个人的成绩
        foreach ($cj as $key => $value) {
            $temjige = 0; # 记录学生及格的学科数
            $col = 0; # 记录有成绩的学生数
            // 开始循环这个学生的每个学科成绩
            foreach ($sbj as $k => $val) {
                if (isset($value[$val['lieming'].'defen'])) {
                    if ($value[$val['lieming'].'defen'] >= $val['fenshuxian'][$category]) {
                        $temjige ++;
                    }
                    if ($value[$val['lieming'].'defen'] !== null) {
                        $col ++;
                    }
                }
            }
            $col > 0 ? $row ++ : $row;
            // 如果这个学生及格学科数等于学科总数，那么全科及格总数加1
            $temjige == $sbjcnt && $temjige > 0 ? $jige ++ : $jige ;
        }

        // $row > 0 ? $rate = round($jige / count($cj) * 100, 2) : $rate = '';
        return $jige;
    }


    /**
     * 得到数组的标准差
     * @param unknown type $avg
     * @param Array $list
     * @param Boolen $isSwatch
     * @return unknown type
     */
    public static  function getVariance($avg, $list, $isSwatch  = FALSE) {
            $arrayCount = count($list);
            if ($arrayCount == 1 && $isSwatch == TRUE) {
                    return '-';
            } elseif ($arrayCount > 0 ) {
                    $total_var = 0;
                    foreach ($list as $lv)
                            $total_var += pow(($lv - $avg), 2);
                    if ($arrayCount == 1 && $isSwatch == TRUE)
                            return FALSE;
                    return $isSwatch?sqrt($total_var / (count($list) - 1 )) : sqrt($total_var / count($list));
            }
            else
                    return '-';
    }


    /**
    * 计算四分位（ Quartile ） Q1、Q2、Q3的值
    * @access public
    * @param array $arr 要计算的一维数组
    * @return array $result 下标0-2 分别对应Q1、Q2、Q3
    */
    public function myquartile($arr)
    {
        // 获取数组长度
        $length = count($arr);

        // 如果数组为空或数组长度小于5时，返回空。
        if ($arr == null || $length == 0) return $result = [0 => '', 1 => '', 2 => ''];
        if ($length < 5) return $result = [0 => '', 1 => '', 2 => ''];

        // 数组排序
        sort($arr);
        // 初始化变量
        $result = array();  #储存结果
        $q = array();       #储存位置

        // 确定位置
        // 计算方法参考百度百科
        // 使用n-1方法。
        $q[0] = 1 + ($length - 1 ) * 0.25;
        $q[1] = 1 + ($length - 1 ) * 0.5;
        $q[2] = 1 + ($length - 1 ) * 0.75;

        // 计算每个位置对应的值
        foreach ($q as $key => $value) {
            if (is_int($value)) {    # 如果位置是整数，则直接取位置对应的值

                $result[$key] = $arr[$value - 1];
            } else {
            # 如果位置不是整数。计算公式:大数*位置小数 + 小数*(1-位置小数)
                $tempNum = $value;
                $temp = intval($tempNum);
                $tempNum = $tempNum - $temp;
                $result[$key] = $arr[$temp] * $tempNum + $arr[$temp - 1] * (1 - $tempNum);
                $result[$key] = round($result[$key], 2);
            }
        }
        return $result;
    }


    // 计算中位数
    public function zhongweishu($arr)
    {
        // 将数据排序
        rsort($arr);
        $zws = 0;

        $cnt = count($arr);
        if ($cnt === 1) {
            $zws = $arr[0];
        } elseif ($cnt == 2) {
            $zws = ($arr[0] + $arr[1]) / 2;
        } elseif ($cnt  > 2) {
            $mod = $cnt % 2;
            if ($mod === 0) {
                $i = $cnt / 2;
                $zws = ($arr[$i] + $arr[$i + 1]) / 2;
            }else{
                $i = ($cnt - 1) / 2;
                $zws = $arr[$i];
            }
        }else{
            $zws = 0;
        }

        return $zws;
    }


    // 计算众数
    public function zhongshu ($arr)
    {
        // 将数据排序
        rsort($arr);
        // 将数据填入数组
        $data = array();
        foreach ($arr as $key => $value) {
            if (isset($data[$value])) {
                $data[$value] = $data[$value] + 1;
            }else{
                $data[$value] = 1;
            }
        }
        // 对数据进行降序排列
        arsort($data);
        $i = 0;
        $zs = array();
        $old = '';
        foreach ($data as $key => $value) {
            if ($value > 2 && $i===0) {
                $zs[] = $key;
            } elseif ($data[$key] === $old) {
                $zs[] = $key;
            }else{
                continue;
            }
            $old = $value;
            $i = $i + 1;
        }
        asort($zs);
        $zs = implode($zs,'、');

        return $zs;
    }


    // 根据给定数据统计分数段
    public function fenshuduan($arr = array(), $manfen = 0, $cishu = 30)
    {
        $cishu == '' ? $cishu = 20 : true;
        // 计算分数段
        $long = round($manfen / $cishu, 3);
        $fenshuduan[] = [
            'low' => '-'.$long
            ,'high' => 0
            ,'cnt' => 0
        ];
        $cnt = 0;
        for($i = 0; $i <= $manfen; $i=$i+$long)
        {
            $fenshuduan[] = [
                'low' => $i
                ,'high' => $i + $long
                ,'cnt' => 0
            ];
        }

        foreach ($arr as $key => $value) {
            $xiabiao = intval($value / $long);
            if($xiabiao < 0){
                $fenshuduan[0]['cnt'] = $fenshuduan[0]['cnt'] + 1;
                continue;
            }
            if (isset($fenshuduan[$xiabiao]))
            {
                $fenshuduan[$xiabiao + 1]['cnt'] = $fenshuduan[$xiabiao + 1]['cnt'] + 1;
                $cnt = $cnt + 1;
            }
        }
        $count = count($arr);
        if ($count > $cnt)
        {
            $last = array_pop($fenshuduan);
            $fenshuduan[] = [
                'low' => $last['high']
                ,'high' => $last['high'] + $long
                ,'cnt' => $count - $cnt
            ];
        }

        return $fenshuduan;
    }


    // 重新计算得分率
    public function updateDfl($srcfrom)
    {
        $src = [
            'kaoshi_id' => 0
            ,'ruxuenian' => 0
            ,'subject_id' => 0
        ];
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $cj = new \app\chengji\model\Chengji;
        $kh = new \app\kaohao\model\Kaohao;
        $ksset = new \app\kaoshi\model\KaoshiSet;

        // 查询考号
        $kaohaoid = $kh->where('kaoshi_id', $src['kaoshi_id'])
            ->where('ruxuenian', $src['ruxuenian'])
            ->column('id');
        // 查询成绩
        $cjList = $cj->where('kaohao_id', 'in', $kaohaoid)
            ->where('subject_id', $src['subject_id'])
            ->whereNotNull('defen')
            ->field('id, defen, defenlv')
            ->select();
        $manfen = $ksset->where('kaoshi_id', $src['kaoshi_id'])
            ->where('subject_id', $src['subject_id'])
            ->where('ruxuenian', $src['ruxuenian'])
            ->value('manfen');
        // 循环写入新成绩
        $data = array();
        foreach ($cjList as $cj_k => $cj_v) {
            $data[] = [
                'id' => $cj_v->id
                ,'defenlv' => $cj_v->defen / $manfen *100
            ];
        }
        $data = $cj->saveAll($data);

        $data ? $data = true : false;

        return $data;
    }


    // 计算标准分
    public function biaoZhunFen($srcfrom)
    {
        $src = [
            'kaoshi_id' => 0
            ,'ruxuenian' => 0
            ,'subject_id' => 0
        ];
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        // $src = 
        $cj = new \app\chengji\model\Chengji;
        $kh = new \app\kaohao\model\Kaohao;
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $schTj = new \app\chengji\model\TongjiSch;

        // 查询考号
        $kaohaoid = $kh->where('kaoshi_id', $src['kaoshi_id'])
            ->where('ruxuenian', $src['ruxuenian'])
            ->column('id');
        // 查询成绩
        $cjList = $cj->where('kaohao_id', 'in', $kaohaoid)
            ->where('subject_id', $src['subject_id'])
            ->whereNotNull('defen')
            ->field('id, defen, defenlv')
            ->select();
        // 查询全区(所有学生)标准差和平均分
        $schJg = $schTj->where('kaoshi_id', $src['kaoshi_id'])
            ->where('ruxuenian', $src['ruxuenian'])
            ->where('subject_id', $src['subject_id'])
            ->whereNotNull('avg&biaozhuncha')
            ->field('avg, biaozhuncha')
            ->find();

        $data = array();
        if ($schJg) {

            // 循环写入新成绩
            foreach ($cjList as $cj_k => $cj_v) {
                $bzf = ($cj_v->defen - $schJg->avg * 1) / ($schJg->biaozhuncha * 1);    #计算标准分
                $bzf = round($bzf, 2);  # 取两位小数
                $data[] = [
                    'id' => $cj_v->id
                    ,'biaozhunfen' => $bzf
                ];
            }
        }

        $cnt = count($data);
        $data = $cj->saveAll($data)->count();
        if($cnt == $data)
        {
            $data = 1;
        }else{
            $data = 0;
        }

        return $data;
    }



}
