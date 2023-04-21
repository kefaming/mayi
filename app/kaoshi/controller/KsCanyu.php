<?php

namespace app\kaoshi\controller;

// 引用控制器基类
use app\base\controller\AdminBase;


class KsCanYu extends AdminBase
{
    // 根据考试ID和年级获取参加考试学校
    public function school()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'ruxuenian' => ''
                ,'kaoshi_id' => ''
                ,'page' => '1'
                ,'limit' => '10'
                ,'field' => 'school_id'
                ,'order' => 'desc'
            ], 'POST');

        $cy = new \app\kaohao\model\SearchCanYu;
        $school = $cy->school($src);
        $src['all'] = true;
        $cnt = count($cy->school($src));
        $data = reset_data($school, $cnt);

        return json($data);
    }


    // 根据考试ID和年级获取参加考试年级
    public function grade()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'page' => '1'
                ,'limit' => '10'
                ,'field' => 'ruxuenian'
                ,'order' => 'desc'
            ], 'POST');
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $nianji = $ksset->srcGrade($src);
        $src['all'] = true;
        $cnt = count($ksset->srcGrade($src));
        $data = reset_data($nianji, $cnt);

        return json($data);
    }


    // 根据考试ID和年级获取参加考试班级
    public function class()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'school_id' => ''
                ,'ruxuenian' => ''
                ,'kaoshi_id' => ''
                ,'page' => '1'
                ,'limit' => '10'
                ,'field' => 'school_id'
                ,'order' => 'desc'
            ], 'POST');
        $khSrc = new \app\kaohao\model\SearchCanYu;

        $src['all'] = true;
        $bj = $khSrc->class($src);
        $src['all'] = true;
        $cnt = count($khSrc->class($src));
        $data = reset_data($bj, $cnt);

        return json($data);
    }


    // // 根据考试ID和年级获取参加考试班级
    // public function tjClass()
    // {
    //     // 获取参数
    //     $src = $this->request
    //         ->only([
    //             'school_id' => ''
    //             ,'ruxuenian' => ''
    //             ,'kaoshi_id' => ''
    //             ,'subject_id' => ''
    //             ,'limit' => 100
    //         ], 'POST');

    //     $khSrc = new \app\chengji\model\TongjiBj;
    //     $bj = $khSrc->searchSubjedt($src);
    //     $src['all'] = true;
    //     $cnt = $khSrc->searchSubjedt($src)->count();
    //     $data = reset_data($data, $cnt);

    //     return json($bj);
    // }


    // 根据考试ID和年级获取已经参加本次考试学科
    public function subject()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'subject_id' => ''
                ,'field' => 'paixu'
                ,'order' => 'asc'
                ,'page' => 1
                ,'limit' => 100
            ], 'POST');

        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src['all'] = true;
        $sbj = $ksset->srcSubject($src);
        $cnt = count($ksset->srcSubject($src));
        $data = reset_data($sbj, $cnt);

        return json($data);
    }
}
