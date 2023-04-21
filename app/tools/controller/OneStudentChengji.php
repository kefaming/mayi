<?php
declare (strict_types = 1);

namespace app\tools\controller;

// 引用控制器基类
use app\BaseController;
// 引用学生数据模型类
use \app\tools\model\OneStudentChengji as STUCJ;


class OneStudentChengji extends BaseController
{
    // 获取学生历次考试所有学科成绩
    public function ajaxOldChengji()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'kaoshi_id'
                ,'order' => 'desc'
                ,'student_id' => ''
                ,'category_id' => ''
                ,'xueqi_id' => ''
                ,'bfdate' => ''
                ,'enddate' => ''
                ,'searchval' => ''
            ], 'POST');

        // 获取学生成绩
        $stucj = new STUCJ;
        $khList = $stucj->oldList($src);
        $data = reset_data($khList, $src);

        return json($data);
    }


    // 学生个人成绩雷达图
    public function ajaxLeiDa()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'kaohao_id' => '',
            ], 'POST');

        // 获取学生成绩
        $stucj = new STUCJ;
        $data = $stucj->leiDa($src);

        return json($data);
    }


    // 学生个人总分仪表图
    public function ajaxYiBiao()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'kaohao_id' => '',
            ], 'POST');

        // 获取学生成绩
        $stucj = new STUCJ;
        $data = $stucj->yiBiao($src);
        
        return json($data);
    }


    // 学生单次各学科成绩列表
    public function ajaxSubjectChengji()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'kaohao_id' => '',
            ], 'POST');

        // 获取学生成绩
        $stucj = new STUCJ;
        $data = $stucj->oneChengji($src);
        $data = reset_data($data, $src);

        return json($data);
    }


    // 学生个人成绩列表
    public function ajaxSubjectDeFenLv()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'kaohao_id' => '',
            ], 'POST');

        // 获取学生成绩
        $stucj = new STUCJ;
        $data = $stucj->oneDeFenLv($src);

        return json($data);
    }


    // 学生个人成绩列表
    public function ajaxSubjectWeiZhi()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'kaohao_id' => '',
            ], 'POST');

        // 获取学生成绩
        $stucj = new STUCJ;
        $data = $stucj->oneSubjectWeiZhi($src);
        return json($data);
    }


    // 单个学生历次成绩（得分率）
    public function ajaxOldSubject()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'student_id' => ''
                ,'kaoshi_id' => array()
                ,'subject_id' => ''
                ,'category_id' => ''
                ,'xueqi_id' => ''
                ,'bfdate' => ''
                ,'enddate' => ''
                ,'searchval' => ''
            ], 'POST');

        $stucj = new STUCJ;
        $data = $stucj->subjectOldChengji($src);
        return json($data);
    }


    // 生成个人报告
    public function ajaxBaoGao()
    {
        // 获取表单参数
        $src = $this->request
            ->only([
                'kaohao_id' => ''
            ], 'POST');

        $stucj = new STUCJ;
        $baogao = $stucj->baogao($src);
        $data = [
            'val' => 1
            ,'msg' => '数据获取成功'
            ,'baogao' => $baogao
        ];

        return json($data);
    }

}
