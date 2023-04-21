<?php

namespace app\chengji\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用成绩统计数据模型
use app\chengji\model\TongjiNj as NTJ;

class Njtongji extends AdminBase
{
    // 获取各学校、各年级考试成绩
    public function biaoge($kaoshi_id)
    {
        // 获取考试信息
        $ks = new \app\kaoshi\model\Kaoshi;
        $ksinfo = $ks->where('id', $kaoshi_id)
            ->field('id, title')
            ->find();
        // 获取参与学校
        $cy = new \app\kaohao\model\SearchCanYu;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        $list['school_id'] = $cy->school($src);
        // 获取年级与学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        
        $list['nianji'] = $ksset->srcGrade($src);
        $list['subject_id'] = $ksset->srcSubject($src);

        // 设置要给模板赋值的信息
        $list['webtitle'] = '各学校的年级成绩统计表';
        $list['kaoshi_id'] = $kaoshi_id;
        $list['kaoshititle'] = $ksinfo->title;
        $list['dataurl'] = '/chengji/njtj/data';
        $list['tjxm'] = src_tjxm(12203);


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
                ,'limit' => '30'
                ,'kaoshi_id' => ''
                ,'ruxuenian' => ''
            ],'POST');

        // 统计成绩
        $ntj = new NTJ;
        $data = $ntj->search($src);
        $data = array_slice($data, ($src['page'] - 1) * $src['limit'], $src['limit']);
        $schtj = new \app\chengji\model\TongjiSch;
        $dataAll = $schtj->search($src);
        if (count($dataAll) > 0) {
            $data[] = $dataAll['all'];
        }

        // 获取记录总数
        $cnt = count($data);
        // 重组返回内容
        $data = [
            'code'=> 0 , // ajax请求次数，作为标识符
            'msg'=>"",  // 获取到的结果数(每页显示数量)
            'count'=>$cnt, // 符合条件的总数据量
            'data'=>$data, //获取到的数据结果
        ];

        return json($data);
    }


    // 年级、班级学生成绩统计结果下载界面
    public function dwBiaoge($kaoshi_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '各年级成绩汇总表下载'
            ,'butname' => '下载'
            ,'formpost' => 'POST'
            ,'url' => '/chengji/njtj/dwxlsx'
            ,'kaoshi_id' => $kaoshi_id
        );

        // 获取年级与学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src['kaoshi_id'] = $kaoshi_id;
        $list['set']['nianji'] = $ksset->srcGrade($src);

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();

    }


    // 年级、班级学生成绩统计下载表格
    public function dwNianjixlsx()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'kaoshi_id' => ''
                ,'ruxuenian' => ''
            ], 'POST');

        // 统计成绩
        $ntj = new NTJ;
        $data = $ntj->search($src);
        $schtj = new \app\chengji\model\TongjiSch;
        $dataAll = $schtj->search($src);
        if (count($dataAll) > 0) {
            $data['all'] = $dataAll['all'];
        }

        // 获取信息
        ob_start();
        $ks = new \app\kaoshi\model\Kaoshi; # 获取参考学科
        $ksinfo = $ks->where('id', $src['kaoshi_id'])
                    ->field('id, title, bfdate, enddate')
                    ->find();
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $xk = $ksset->srcSubject($src);
        $njlist = \app\facade\Tools::nianJiNameList($ksinfo->getData('bfdate')); # 获取考试年级名称
        $nianji = $njlist[$src['ruxuenian']];
        $tabletitle = $ksinfo->title . ' ' . $nianji . '各学校成绩汇总'; # 获取要下载成绩的学校和年级信息

        //创建文件
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $thistime = date("Y-m-d h:i:sa");

        // 设置文档属性
        $spreadsheet->getProperties()
            ->setCreator("码蚁成绩管理系统")    //作者
            ->setTitle("码蚁成绩管理")  //标题
            ->setLastModifiedBy(session('username')) //最后修改者
            ->setDescription("该表格由".session('username').session('id')."于".$thistime."在码蚁成绩管理系统中下载，只作为内部交流材料,不允许外泄。")  //描述
            ->setKeywords("码蚁 成绩管理") //关键字
            ->setCategory("成绩管理"); //分类

        // 设置导出项目、列表等
        $sbjcol = src_tjxm(12204);
        $sbjcolcnt = count($sbjcol);
        $colname = excel_column_name();
        $colcnt = $sbjcolcnt * count($xk) + 3;

        // 设置表头信息
        $sheet->mergeCells('A1:' . $colname[$colcnt] . '1');
        $sheet->setCellValue('A1', $tabletitle);
        $sheet->mergeCells('A2:' . $colname[$colcnt] . '2');
        $sheet->setCellValue('A2', '考试时间：' . $ksinfo->bfdate . ' ~ '. $ksinfo->enddate);
        $sheet->mergeCells('A3:A4');
        $sheet->setCellValue('A3', '序号');
        $sheet->mergeCells('B3:B4');
        $sheet->setCellValue('B3', '学校');
        $col = 2;
        foreach ($xk as $key => $value) {
            $colend = $col + $sbjcolcnt - 1;
            $sheet->mergeCells($colname[$col] . '3:' . $colname[$colend] . '3');
            $sheet->setCellValue($colname[$col] . '3', $value['title'] . ' (' . $value['fenshuxian']['manfen'] . ')');
            foreach ($sbjcol as $k => $val) {
                 $sheet->setCellValue($colname[$col] . '4', $val['title']);
                 $col++;
            }
        }
        $sheet->mergeCells($colname[$col] . '3:' . $colname[$col] . '4');
        $sheet->setCellValue($colname[$col] . '3', '全科及格率%');
        $col++;
        $sheet->mergeCells($colname[$col] . '3:' . $colname[$col] . '4');
        $sheet->setCellValue($colname[$col] . '3', '总平均分');
        $sheet->getStyle('C3:' . $colname[$col] . '4')->getAlignment()->setWrapText(true);

        // 写入统计结果
        $row = 5;
        foreach ($data as $key => $value) {
            $col = 2;
            $sheet->setCellValue('A' . $row, $row - 4);
            $sheet->setCellValue('B' . $row, $value['school_jiancheng']);
            foreach ($xk as $ke => $val) {
                foreach ($sbjcol as $k => $v) {
                    $sheet->setCellValue($colname[$col] . $row, $value['chengji'][$val['lieming']][$v['biaoshi']]);
                    $col ++;
                }
            }
            $sheet->setCellValue($colname[$col] . $row, $value['quanke']['jigelv']);
            $col ++;
            $sheet->setCellValue($colname[$col] . $row, $value['quanke']['avg']);
            $row ++;
        }

        // 文档排版
        $styleArray = [ # 居中
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, //垂直居中
            ],
        ];
        $sheet->getStyle('A1:' . $colname[$col] . ($row - 1))->applyFromArray($styleArray);
        // 表格格式
        $styleArrayJZ = [  # 居中
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $sheet->getStyle('A2:' . $colname[$col] . '2')->applyFromArray($styleArrayJZ);
        $styleArray = [ # 加边框
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $sheet->getStyle('A3:' . $colname[$col] . ($row - 1))->applyFromArray($styleArray);

        $sheet->getStyle('A1')->getFont()->setBold(true)->setName('宋体')->setSize(20); # 修改标题字号
        $sheet->getDefaultRowDimension()->setRowHeight(35); # 设置行高
        $sheet->getRowDimension(3)->setRowHeight(25);;
        $sheet->getRowDimension(4)->setRowHeight(25);;
        $sheet->getDefaultColumnDimension()->setWidth(8.3); # 设置列宽
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10.5);

        // 页面设置
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageMargins()->setTop(0.8); # 设置页边距
        $sheet->getPageMargins()->setRight(0.2);
        $sheet->getPageMargins()->setLeft(0.2);
        $sheet->getPageMargins()->setBottom(0.8);
        $sheet->getPageSetup()->setHorizontalCentered(true); # 打印居中
        $sheet->getPageSetup()->setVerticalCentered(false);

        // 保存文件
        $filename = $tabletitle . date('ymdHis') . '.xlsx';
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
        $ntj = new NTJ;
        $data = $ntj->tjNianji($kaoshi_id);

        if (true == $data) {
            $data = [
                'msg'=>'完成'
                ,'val'=>1
            ];
            $src = [
                'kaoshi_id' => $kaoshi_id
                ,'category_id' => 12003
            ];
            event('tjlog', $src);
        } else {
            $data = [
                'msg' => '数据处理错误'
                ,'val' => 0
            ];
        }

        return json($data);
    }


    // 统计学生在班级名次
    public function njOrder()
    {
        // 获取变量
        $kaoshi_id = input('post.kaoshi_id');
        // 判断考试状态
        event('kstj', $kaoshi_id);

        // 统计成绩
        $ntj = new NTJ;
        $data = $ntj->njOrder($kaoshi_id);

        if (true == $data) {
            $data = [
                'msg' => '完成'
                ,'val' => 1
            ];
            $src = [
                'kaoshi_id' => $kaoshi_id
                ,'category_id' => 12004
            ];
            event('tjlog', $src);
        } else {
            $data = [
                'msg' => '数据处理错误'
                ,'val' => 0
            ];
        }

        return json($data);
    }


    // 统计平均分优秀率及格率
    public function myAvg()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
                ,'xiangmu' => ''
            ], 'POST');

        // 统计成绩
        $ntj = new NTJ;
        $data = $ntj->search($src);
        $ntj = new \app\chengji\model\TongjiBj;
        $data = tiaoxingOnexiangmu($data, $src['xiangmu']);

        return json($data);
    }


    // 统计平均分优秀率及格率
    public function myXiangti()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => ''
                ,'ruxuenian' => ''
                ,'school_id' => array()
            ], 'POST');


        // 统计成绩
        $ntj = new NTJ;
        $data = $ntj->search($src);
        $data = xiangti($data);

        return json($data);
    }

}
