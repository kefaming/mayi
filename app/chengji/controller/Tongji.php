<?php

namespace app\chengji\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用成绩统计数据模型
use app\chengji\model\Tongji as TJ;

class Tongji extends AdminBase
{
    // 批量统计得分率
    public function updateDefenLv() {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => 0
                ,'ruxuenian'
                ,'subject_id'
            ], 'POST');

        $tj = new TJ();
        if(isset($src['ruxuenian']) && isset($src['subject_id']))
        {
            $data = $tj->updateDfl($src);
        }else{
            $ksset = new \app\kaoshi\model\KaoshiSet;

            // 重新计算得分率
            $nj = $ksset->srcGrade($src); # 获取参考年级
            foreach ($nj as $key => $value) { # 循环获取各年级参考学科
                $src['ruxuenian'] = $value['ruxuenian'];
                $manfen = $ksset->srcSubject($src); # 获取本年级各学科满分
                // 循环取出当前学科成绩
                foreach ($manfen as $mf_k => $mf_v) {
                    $src['subject_id'] = $mf_v['id'];
                    $data = $tj->updateDfl($src);
                }
            }
        }
        
        $data ? $data = ['msg' => '完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    public function newJieguo($kaoshi_id)
    {
        // 获取考试信息
        $ks = new \app\kaoshi\model\Kaoshi;
        $ksinfo = $ks->where('id', $kaoshi_id)
            ->field('id, title')
            ->find();

        // 设置要给模板赋值的信息
        $list['webtitle'] = '最新统计结果状态';
        $list['kaoshi'] = $kaoshi_id;
        $list['kaoshititle'] = $ksinfo->title;
        $list['dataurl'] = '/chengji/tongji/jgdata';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 统计标准分
    public function biaoZhunFen()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => 0
                ,'ruxuenian'
                ,'subject_id'
            ], 'POST');

        $tj = new TJ();
        if(isset($src['ruxuenian']) && isset($src['subject_id']))
        {
            $data = $tj->biaoZhunFen($src);
        }else{
            $ksset = new \app\kaoshi\model\KaoshiSet;

            // 重新计算得分率
            $nj = $ksset->srcGrade($src); # 获取参考年级
            $jg = 1;
            foreach ($nj as $key => $value) { # 循环获取各年级参考学科
                $src['ruxuenian'] = $value['ruxuenian'];
                $manfen = $ksset->srcSubject($src); # 获取本年级各学科满分
                // 循环取出当前学科成绩
                foreach ($manfen as $mf_k => $mf_v) {
                    $src['subject_id'] = $mf_v['id'];
                    $data = $tj->biaoZhunFen($src);
                    $jg = $jg * $data;
                }
            }
        }
       
        $data ? $data = ['msg' => '完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
