<?php

namespace app\chengji\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用成绩统计数据模型
use app\chengji\model\TongjiBj as BTJ;

class Bjtongji extends AdminBase
{
    // 班级成绩汇总
    public function biaoge($kaoshi_id)
    {
        // 获取考试信息
        $ks = new \app\kaoshi\model\Kaoshi;
        $ksinfo = $ks->where('id', $kaoshi_id)
            ->field('id, title')
            ->find();
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        // 获取年级与学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $list['subject_id'] = $ksset->srcSubject($src);
        // 设置要给模板赋值的信息
        $list['webtitle'] = '各年级的班级成绩列表';
        $list['kaoshi_id'] = $kaoshi_id;
        $list['kaoshititle'] = $ksinfo->title;
        $list['dataurl'] = '/chengji/bjtj/data';
        $list['tjxm'] = src_tjxm(12201);

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取年级成绩统计结果
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
                ,'banji_id' => array()
                ,'auth' => []
            ], 'POST');

        if (count($src['banji_id'])==0) {
            // 获取参与考试的班级
            $cy = new \app\kaohao\model\SearchCanYu;
            $src['banji_id']= array_column($cy->class($src),'id');
        }

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];

        // 统计成绩
        $btj = new BTJ;
        $data = $btj->search($src);
        $data = reset_data($data, $src);

        return json($data);
    }


    // 班级成绩汇总
    public function fenshuduan($kaoshi_id)
    {
        // 获取考试信息
        $ks = new \app\kaoshi\model\Kaoshi;
        $ksinfo = $ks->where('id', $kaoshi_id)
            ->field('id, title')
            ->find();
        // 获取参与学校
        $cy = new \app\kaohao\model\SearchCanYu;
        $src = [
            'kaoshi_id'=>$kaoshi_id
        ];
        $list['school_id'] = $cy->school($src);


        // 获取年级与学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $list['nianji'] = $ksset->srcGrade($src);
        $list['subject_id'] = $ksset->srcSubject($src);
        // 设置要给模板赋值的信息
        $list['webtitle'] = '各年级的班级成绩列表';
        $list['kaoshi_id'] = $kaoshi_id;
        $list['kaoshititle'] = $ksinfo->title;
        $list['dataurl'] = '/';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 年级、班级学生成绩统计结果下载界面
    public function dwBiaoge($kaoshi_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '各班级成绩汇总表下载'
            ,'butname' => '下载'
            ,'formpost' => 'POST'
            ,'url' => '/chengji/bjtj/dwxlsx'
            ,'kaoshi_id' => $kaoshi_id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();

    }


    // 年级、班级学生成绩统计下载表格
    public function dwBanjixls()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
                ,'banji_id' => array()
                ,'auth' => []
                ,'all' => true
            ], 'POST');

        if (count($src['banji_id'])==0) {
            // 获取参与考试的班级
            $cy = new \app\kaohao\model\SearchCanYu;
            $src['banji_id']= array_column($cy->class($src),'id');
        }

        $src['auth'] = event('mybanji', $src['auth']);
        $src['auth'] = $src['auth'][0];
        $src['school_id'] = str_to_array($src['school_id']);

        // 获取相关参数
        ob_start();
        $ks = new \app\kaoshi\model\Kaoshi; # 参考学科
        $ksinfo = $ks->where('id', $src['kaoshi_id'])
                    ->field('id, title, bfdate, enddate')
                    ->find();
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $xk = $ksset->srcSubject($src);
        $njlist = \app\facade\Tools::nianJiNameList($ksinfo->getData('bfdate'));   # 参考年级
        $nianji = $njlist[$src['ruxuenian']];
        $school = new \app\system\model\School; # 学校、年级信息
        $schoolname = $school->where('id', 'in', $src['school_id'])->value('jiancheng');
        $tabletitle = $ksinfo->title . ' ' . $schoolname . $nianji . '各班级成绩汇总';
        $cy = new \app\kaohao\model\SearchCanYu;  # 参考班级
        $src['banji_id']= array_column($cy->class($src), 'id');
        // // 用户职务判断
        // if (session('user_id') !=1 && session('user_id') !=2) {
        //     $qxBanjiIds = event('mybanji');
        //     if (is_array($qxBanjiIds[0])) {
        //         $src['banji_id'] = array_intersect($src['banji_id'], $qxBanjiIds[0]);
        //     }
        // }
        $btj = new BTJ;     # 成绩统计结果
        $data = $btj->search($src);

        // 查询是否需要判断权限 如果不是学校领导和管理员则不写入年级成绩
        $user_id = session("user_id");
        $userInfo = \app\facade\OnLine::myInfo();
        if($userInfo->zhiwu_id == 10701 || $userInfo->zhiwu_id == 10703 || $userInfo->zhiwu_id == 10705 || $user_id <= 2)
        {
            $ntj = new \app\chengji\model\TongjiNj;
            $dataAll = $ntj->search($src);
            count($dataAll) > 0 ? $data['all'] = $dataAll[0] : $data;
        }

        // 将数据写入表格中
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;  # 创建文档
        $sheet = $spreadsheet->getActiveSheet();
        $thistime = date("Y-m-d h:i:sa");
        $spreadsheet->getProperties()   # 设置文档属性
            ->setCreator("码蚁成绩管理系统")    //作者
            ->setTitle("码蚁成绩管理")  //标题
            ->setLastModifiedBy(session('username')) //最后修改者
            ->setDescription("该表格由" . session('username') . session('id') . "于" . $thistime . "在码蚁成绩管理系统中下载，只作为内部交流材料,不允许外泄。")  //描述
            ->setKeywords("码蚁 成绩管理") //关键字
            ->setCategory("成绩管理"); //分类

        // 设置要导出的项目、列名、起始行
        $sbjcol = src_tjxm(12202);
        $sbjcolcnt = count($sbjcol);
        $colname = excel_column_name();
        $colcnt = $sbjcolcnt*count($xk)+3;

        // 设置表头信息
        $sheet->mergeCells('A1:' . $colname[$colcnt] . '1');
        $sheet->setCellValue('A1', $tabletitle);
        $sheet->mergeCells('A2:' . $colname[$colcnt] . '2');
        $sheet->setCellValue('A2', '考试时间：' . $ksinfo->bfdate . ' ～ '. $ksinfo->enddate);
        $sheet->mergeCells('A3:A4');
        $sheet->setCellValue('A3', '序号');
        $sheet->mergeCells('B3:B4');
        $sheet->setCellValue('B3', '班级');
        $col = 2;
        foreach ($xk as $key => $value) {   # 把学科信息定入表头
            $colend = $col + $sbjcolcnt - 1;
            $sheet->mergeCells($colname[$col].'3:'.$colname[$colend].'3');
            $sheet->setCellValue($colname[$col].'3', $value['title'].' ('.$value['fenshuxian']['manfen'].')');
            foreach ($sbjcol as $k => $val) {
                 $sheet->setCellValue($colname[$col].'4', $val['title']);
                 $col++;
            }
        }
        $sheet->mergeCells($colname[$col].'3:'.$colname[$col].'4');
        $sheet->setCellValue($colname[$col].'3', '全科及格率%');
        $col++;
        $sheet->mergeCells($colname[$col].'3:'.$colname[$col].'4');
        $sheet->setCellValue($colname[$col].'3', '全科平均');
        $sheet->getStyle('C3:'.$colname[$col].'4')->getAlignment()->setWrapText(true);

        $row = 5;      # 循环写入成绩
        foreach ($data as $key => $value) {
            $col = 2;
            $sheet->setCellValue('A'.$row, $row-4);
            if($key === 'all')
            {
                $sheet->setCellValue('B'.$row, '合计');
                $a = 'hj';
            }else{
                $sheet->setCellValue('B'.$row, $value['banji_title']);
                $a = 'val';
            }

            foreach ($xk as $ke => $val) {
                foreach ($sbjcol as $k => $v) {
                     $sheet->setCellValue($colname[$col].$row, $value['chengji'][$val['lieming']][$v['biaoshi']]);
                     $col++;
                }
            }
            $sheet->setCellValue($colname[$col].$row, $value['quanke']['jigelv']);
            $col++;
            $sheet->setCellValue($colname[$col].$row, $value['quanke']['avg']);
            $row++;
        }

        // 设置表格格式
        $styleArray = [   # 居中
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, //垂直居中
            ],
        ];
        $sheet->getStyle('A1:'.$colname[$col].($row-1))->applyFromArray($styleArray);
        $styleArrayJZ = [  # 居中
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $sheet->getStyle('A2:'.$colname[$col].'2')->applyFromArray($styleArrayJZ);
        $styleArray = [ # 加边框
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $sheet->getStyle('A3:'.$colname[$col].($row-1))
            ->applyFromArray($styleArray);
        $sheet->getStyle('A1')->getFont()->setBold(true)
            ->setName('宋体')->setSize(20); # 修改标题字号
        $sheet->getDefaultRowDimension()->setRowHeight(35); # 设置行高
        $sheet->getRowDimension('3')->setRowHeight(25);
        $sheet->getRowDimension('4')->setRowHeight(25);
        $sheet->getDefaultColumnDimension()->setWidth(8.3); # 设置列宽
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10.5);

        // 页面设置
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE); # 横向
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);  # 大小A4
        $sheet->getPageMargins()->setTop(0.8); # 页边距
        $sheet->getPageMargins()->setRight(0.2);
        $sheet->getPageMargins()->setLeft(0.2);
        $sheet->getPageMargins()->setBottom(0.8);
        $sheet->getPageSetup()->setHorizontalCentered(true);  # 打印居中
        $sheet->getPageSetup()->setVerticalCentered(false);


        // 保存文件
        $filename = $tabletitle . date('ymdHis', time()) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        $writer->save('php://output');
        ob_flush();
        flush();
        exit();
    }


    // 统计各班级成绩
    public function tongji()
    {
        // 获取变量
        $kaoshi_id = input('post.kaoshi_id');
        // 判断考试状态
        event('kstj', $kaoshi_id);

        // 统计成绩
        $btj = new BTJ;
        $data = $btj->tjBanji($kaoshi_id);

        if (true == $data) {
            $data = ['msg' => '完成', 'val' => 1];
            $src = [
                'kaoshi_id' => $kaoshi_id,
                'category_id' => 12001,
            ];
            event('tjlog', $src);
        } else {
            $data = ['msg' => '数据处理错误', 'val' => 0];
        }

        return json($data);
    }


    // 统计学生在班级名次
    public function bjOrder()
    {
        // 获取变量
        $kaoshi_id = input('post.kaoshi_id');
        // 判断考试状态
        event('kstj', $kaoshi_id);

        // 统计成绩
        $btj = new BTJ;
        $data = $btj->bjOrder($kaoshi_id);

        if (true == $data) {
            $data = ['msg' => '完成', 'val' => 1];
            $src = [
                'kaoshi_id' => $kaoshi_id
                ,'category_id' => 12002
            ];
            event('tjlog', $src);
        } else {
            $data = ['msg' => '数据处理错误', 'val' => 0];
        }

        return json($data);
    }


    // 统计平均分优秀率及格率
    public function myAvg($kaoshi_id)
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
                ,'banji_id' => array()
                ,'xiangmu' => ''
            ], 'POST');

        if (count($src['banji_id']) == 0) {
            // 获取参与考试的班级
            $cy = new \app\kaohao\model\SearchCanYu;
            $src['banji_id'] = array_column($cy->class($src), 'id');
        }

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];

        // 统计成绩
        $btj = new BTJ;
        $data = $btj->search($src);
        $data = tiaoxingOnexiangmu($data, $src['xiangmu']);

        return json($data);
    }


    // 统计平均分优秀率及格率
    public function myXiangti($kaoshi_id)
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
                ,'banji_id' => array()
            ], 'POST');

        if (count($src['banji_id']) == 0) {
            // 获取参与考试的班级
            $cy = new \app\kaohao\model\SearchCanYu;
            $src['banji_id'] = array_column($cy->class($src), 'id');
        }

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];

        // 统计成绩
        $btj = new BTJ;
        $data = $btj->search($src);
        $data = xiangti($data);

        return json($data);
    }


    // 分数段
    public function myFenshuduan($kaoshi_id)
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
                ,'banji_id' => array()
                ,'subject_id' => ''
                ,'cishu' => 20
            ], 'POST');

        if (count($src['banji_id']) == 0) {
            // 获取参与考试的班级
            $cy = new \app\kaohao\model\SearchCanYu;
            $src['banji_id']= array_column($cy->class($src), 'id');
        }

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];

        // 统计成绩
        $btj = new BTJ;
        $fsd = $btj->tjBanjiFenshuduan($src);

        $data = array();
        foreach ($fsd as $key => $value) {
            $data['xAxis'][] = (string)$value['low'].'~'.$value['high'];
            $data['series'][] = $value['cnt'];
        }

        return json($data);
    }


    // 班级任课教师列表
    public function renke($kaoshi_id)
    {
        // 获取考试信息
        $ks = new \app\kaoshi\model\Kaoshi;
        $ksinfo = $ks->where('id', $kaoshi_id)
            ->field('id, title')
            ->find();
        // 获取参与学校
        $cy = new \app\kaohao\model\SearchCanYu;
        $src['kaoshi_id'] = $kaoshi_id;
        $list['school_id'] = $cy->school($src);

        // 获取年级与学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        $list['nianji'] = $ksset->srcGrade($kaoshi_id);
        $list['subject_id'] = $ksset->srcSubject($src);
        // 设置要给模板赋值的信息
        $list['webtitle'] = '任课教师';
        $list['kaoshi_id'] = $kaoshi_id;
        $list['kaoshititle'] = $ksinfo->title;
        $list['dataurl'] = '/chengji/bjtj/renkedata';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 查询任课教师
    // 获取年级成绩统计结果
    public function ajaxDataRenke()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
                ,'banji_id' => array()
                ,'auth' => []
            ], 'POST');

        if (count($src['banji_id'])==0) {
            // 获取参与考试的班级
            $cy = new \app\kaohao\model\SearchCanYu;
            $src['banji_id']= array_column($cy->class($src),'id');
        }

        $src['auth'] = event('mybanji', $src['auth']);
        $src['auth'] = $src['auth'][0];
        if(count($src['banji_id']) > 0)        # 如果没有获取到班级id,则查询班级id
        {
            $src['auth']['banji_id'] = array_intersect($src['auth']['banji_id'], $src['banji_id']);
        }

        // 统计成绩
        $btj = new BTJ;
        $data = $btj->renke($src);

        // 获取记录总数
        $cnt = count($data);
        // 截取当前页数据
        $data = array_slice($data, ($src['page'] - 1) * $src['limit'], $src['limit']);
        // 重组返回内容
        $data = [
            'code'=> 0 , // ajax请求次数，作为标识符
            'msg'=>"",  // 获取到的结果数(每页显示数量)
            'count'=>$cnt, // 符合条件的总数据量
            'data'=>$data, //获取到的数据结果
        ];

        return json($data);
    }


    // 编辑任课教师
    public function renkeEdit($id)
    {
        // 获取考试ID和班级ID
        $info = BTJ::field('kaoshi_id, banji_id')
            ->find($id);

        $bj = new \app\teach\model\Banji;
        $school = $bj->where('id', $info->banji_id)->value('school_id');

        // 查询该班级各学科任课教师
        $btj = new BTJ;
        $list['data'] = $btj->where('kaoshi_id', $info->kaoshi_id)
                ->where('banji_id', $info->banji_id)
                ->where('subject_id', '>', 0)
                ->field('id, kaoshi_id, banji_id, subject_id')
                ->with([
                    'bjSubject' => function ($query) {
                        $query ->field('id, title, jiancheng');
                    }
                ])
                ->select();

        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'设置任课教师'
            ,'butname'=>'修改'
            ,'formpost'=>'PUT'
            ,'school_id' => $school
            ,'url'=>'/chengji/bjtj/renkeupdate/' . $id,
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }



    // 更新任课教师
    public function renkeUpdate($id)
    {
        // 获取参数
        $src = $this->request
            ->only([
                'teacher_id' => array()
                ,'id' => array()
            ], 'POST');

        // 验证表单数据
        $validate = new \app\chengji\validate\RenKe;
        $result = $validate->scene('edit')->check($src);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg'=>$msg,'val'=>0]);;
        }

        // 整理数据
        $list = array();
        foreach ($src['id'] as $key => $value) {
            if(strlen($src['teacher_id'][$key]) > 0)
            {
                $list[$key] = [
                    'id' => $value
                    ,'teacher_id' => $src['teacher_id'][$key]
                ];
            }
        }

        $btj = new BTJ;
        $data = $btj->saveAll($list);
        $data ? $data = ['msg' => '设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }



    // 根据教师姓名设置任课教师
    public function renkeEditTeacher($kaoshi_id)
    {
        $list['data']['kaoshi_id'] = $kaoshi_id;
        $sbj = new \app\teach\model\Subject;
        $src = [
            'kaoshi' => 1
            ,'status' => 1
        ];
        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'设置任课教师'
            ,'butname'=>'设置'
            ,'formpost'=>'PUT'
            ,'url'=>'/chengji/bjtj/renkeupdateteaher',
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 更新任课教师
    public function renkeUpdateteacher()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'teacher_id' => ''
                ,'banji_id' => array()
                ,'subject_id'  => ''
                ,'kaoshi_id' => ''
            ], 'POST');


        // 验证表单数据
        $validate = new \app\chengji\validate\RenKe;
        $result = $validate->scene('editteacher')->check($src);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg'=>$msg,'val'=>0]);;
        }

        // 整理数据
        $btj = new BTJ;
        foreach ($src['banji_id'] as $key => $value) {
            $map = [
                ['kaoshi_id', '=', $src['kaoshi_id']],
                ['banji_id', '=', $value],
                ['subject_id', '=', $src['subject_id']],
            ];
            $btjInfo = $btj->where([ $map ])->find();
            if($btjInfo)
            {
                $btjInfo->teacher_id = $src['teacher_id'];
                $data = $btjInfo->save();
            }
        }

        $data ? $data = ['msg' => '设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 查询已经有统计结果的学科
    public function srcSubject()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'school_id' => ''
                ,'ruxuenian' => ''
                ,'limit' => 100
            ], 'POST');
        $btj = new BTJ();
        // 获取参考年级
        $data = $btj->srcSubject($src);
        $data = reset_data($data, $src);

        return json($data);
    }


}
