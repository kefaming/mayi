<?php
declare (strict_types = 1);

namespace app\student\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用学生数据模型类
use \app\student\model\Student as STU;
// 引用学生数据模型类
use \app\student\model\StudentChengji as STUCJ;


class Chengji extends AdminBase
{
    // 学生信息页
    public function index($id)
    {
        // 查询学生信息
        $myInfo = STU::withTrashed()
            ->where('id', $id)
            ->with([
                'stuBanji'=>function($query){
                    $query->field('id, school_id, ruxuenian, paixu')
                        ->with([
                            'glSchool' => function($q){
                                $q->field('id,title,jiancheng');
                            }
                        ])
                        ->append(['banjiTitle']);
                }
            ])
            ->find();

        // 获取参加考试学科
        $sbj = new \app\teach\model\Subject;
        $src = ['kaoshi'=>1];
        $ksSbj = $sbj->search($src)->toArray(); 

        $myInfo['sbj'] = $ksSbj;
        // 设置页面标题
        $myInfo['webtitle'] = $myInfo->xingming . ' － 成绩';
        $myInfo['dataurl'] = '/tools/onestudentchengji/oldcj';
        $myInfo['student_id'] = $id;

        // 模板赋值
        $this->view->assign('list', $myInfo);
        // 渲染模板
        return $this->view->fetch('read');
    }



}
