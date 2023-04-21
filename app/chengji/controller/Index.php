<?php
namespace app\chengji\controller;

use app\base\controller\AdminBase;     # 引用控制器基类
use app\chengji\model\Chengji; # 引用成绩数据模型
use \app\kaohao\model\Kaohao; # 引用考号数据模型
use \app\teach\model\Subject; # 引用学科数据模型

class Index extends AdminBase
{
    // 成绩列表
    public function index($kaoshi_id)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '学生成绩列表';

        // 获取参加考试的年级和学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];

        $list['set']['nianji'] = $ksset->srcGrade($src);
        $list['set']['subject_id'] = $ksset->srcSubject($src);
        if (count($list['set']['nianji']) > 0) {
            $src['ruxuenian']=$list['set']['nianji'][0];
        } else {
            $src['ruxuenian']=array();
        }
        $cy = new \app\kaohao\model\SearchCanYu;
        $list['set']['school_id'] = $cy->school($src);
        $list['kaoshi_id'] = $kaoshi_id;
        $list['dataurl'] = '/chengji/index/data';
        $list['tjxm'] = src_tjxm(12205);

        // 模板赋值
        $this->view->assign('list', $list);
        return $this->view->fetch();
    }


    // 获取成绩信息
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id'
                ,'school_id'
                ,'ruxuenian'
                ,'banji_id' => array()
                ,'searchval'
                ,'page' => 1
                ,'limit' => 10
                ,'field' => 'id'
                ,'order' => 'asc'
            ], 'POST');

        // 获取参与考试的班级
        if (count($src['banji_id']) == 0) {
            $cy = new \app\kaohao\model\SearchCanYu;
            $src['all'] = true;
            $src['banji_id']= array_column($cy->class($src), 'id');
        }

        $ks = new \app\kaoshi\model\Kaoshi;
        $ksInfo = $ks->kaoshiInfo($src['kaoshi_id']);

        $src['auth'] = event('mybanji', array('time'=>$ksInfo->getData("bfdate")));
        $src['auth'] = $src['auth'][0];

        // 实例化并查询成绩
        $cj = new Chengji;
        $data = $cj->search($src);
        $data = \app\facade\Tools::reset_array($data, $src);

        return json($data);
    }


    // 批量删除成绩
    public function deletecjs($kaoshi_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '批量删除成绩',
            'butname' => '删除',
            'formpost' => 'POST',
            'url' => '/chengji/index/deletecjmore',
            'kaoshi_id' => $kaoshi_id
        );
        // 获取参加考试的学校和年级
        $cy = new \app\kaohao\model\SearchCanYu;
        $src['kaoshi_id'] = $kaoshi_id;
        $src['all'] = true;
        $list['school'] = $cy->school($src);
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $list['nianji'] = $ksset->srcGrade($src);

        // 模板赋值
        $this->view->assign('list', $list);
        return $this->view->fetch();
    }


    // 按条件删除成绩
    public function deletecjmore()
    {
        // 获取参数
        $src = $this->request->only([
            'kaoshi_id'
            ,'banji_id'
            ,'subject_id'
        ], 'POST');
        $banji_id = $src['banji_id'];
        $subject_id = $src['subject_id'];
        // 判断考试状态
        event('kslu', $src['kaoshi_id']);

        // 实例化验证模型
        $validate = new \app\chengji\validate\Cjdownload;
        $result = $validate->scene('deletemore')->check($src);
        $msg = $validate->getError();
        if(!$result){
            $this->error($msg);
        }

        // 获取要删除成绩的考号
        $kaohao = new \app\kaohao\model\Kaohao;
        $kaohaolist = $kaohao::where('kaoshi_id', $src['kaoshi_id'])
            ->where('banji_id', 'in', $banji_id)
            ->column('id');

        // 删除成绩
        $data = Chengji::destroy(function ($query) use ($kaohaolist, $subject_id) {
            $query->where('kaohao_id', 'in', $kaohaolist)
                  ->where('subject_id', 'in', $subject_id);
        });

        if ($data) {
            $this->success(
                $msg = '删除成功'
                ,$url = '/chengji/index/deletecjs/' . $src['kaoshi_id'], $data = ''
                ,$wait = 3
                ,$header = []
            );
        } else {
            $this->error(
                $msg = '删除失败'
                ,$url = '/chengji/index/deletecjs/' . $src['kaoshi_id']
                ,$wait = 3
            );
        }

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '成绩删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除单人单科成绩
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $khid = Chengji::where('id', $id['0'])->value('kaohao_id');
        $kaoshi_id = Kaohao:: where('id', $khid)->value('Kaoshi_id');

        // 判断考试状态
        event('kslu', $kaoshi_id);
        $data = Chengji::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置考试状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        $khid = Chengji::where('id', $id)->value('kaohao_id');
        $kaoshi_id = Kaohao:: where('id', $khid)->value('Kaoshi_id');
        event('kslu', $kaoshi_id);

        // 获取考试信息
        $data = Chengji::where('id', $id)->update(['status' => $value]);
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 下载成绩表格
    public function dwChengji($kaoshi_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '成绩下载'
            ,'butname' => '下载'
            ,'formpost' => 'POST'
            ,'url' => '/chengji/index/dwxlsx'
            ,'kaoshi_id' => $kaoshi_id
        );

        // 获取参加考试的学校和年级
        $cy = new \app\kaohao\model\SearchCanYu;
        $src['kaoshi_id'] = $kaoshi_id;

        $list['school_id'] = $cy->school($src);
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $list['nianji'] = $ksset->srcGrade($src);

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    //生成学生成绩表格
    public function dwchengjixlsx()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id'=>'1'
                ,'banji_id'=>array()
                ,'school_id'
                ,'ruxuenian'
                ,'subject_id'
            ], 'POST');

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];
        if(count($src['banji_id']) > 0)        # 如果没有获取到班级id,则查询班级id
        {
            $src['auth']['banji_id'] = array_intersect($src['auth']['banji_id'], $src['banji_id']);
        }else{
            $this->error('哎呀~没有足够权限！', '/login/err');
        }

        // 获取要下载成绩的学校和年级信息
        $school = new \app\system\model\School;
        $schoolname = $school->where('id', $src['school_id'])->value('jiancheng');

        // 实例化验证模型
        $validate = new \app\chengji\validate\Cjdownload;
        $result = $validate->scene('download')->check($src);
        $msg = $validate->getError();
        if(!$result){
            $this->error($msg);
        }

        // 获取参与考试的班级
        ob_start();
        $more = new \app\kaohao\model\SearchMore;
        $src['all'] = true;
        $chengjiinfo = $more->srcChengjiList($src);
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $subject_id = $ksset->srcSubject($src);
        $tjxm_left = src_tjxm(12210);
        $tjxm_right = src_tjxm(12206);

        // 获取考试标题
        $ks = new \app\kaoshi\model\Kaoshi;
        $ks = $ks->where('id', $src['kaoshi_id'])
                ->field('id, title, bfdate, enddate')
                ->find();

        // 获取考试年级名称
        $njlist = \app\facade\Tools::nianJiNameList($ks->getData('bfdate'));
        $nianji = $njlist[$src['ruxuenian']];
        $tabletitle = $ks->title . ' ' . $schoolname . ' ' . $nianji . ' ' . '学生成绩详细列表';
        $colname = excel_column_name();

        // set_time_limit(0);
        // 创建表格
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $thistime = date("Y-m-d h:i:sa");
        // 设置文档属性
        $spreadsheet->getProperties()
            ->setCreator("码蚁成绩管理系统")    //作者
            ->setTitle("码蚁成绩管理")  //标题
            ->setLastModifiedBy(session('username')) //最后修改者
            ->setDescription("该表格由" . session('username') . session('id') . "于" . $thistime . "在码蚁成绩管理系统中下载，只作为内部交流材料,不允许外泄。")  //描述
            ->setKeywords("码蚁 成绩管理") //关键字
            ->setCategory("成绩管理"); //分类


        // 设置表头信息
        $row = 1;
        $sheet->setCellValue('A' . $row, $tabletitle);
        $row ++;
        $sheet->setCellValue('A' . $row, '考试时间：' . $ks->bfdate . ' ～ '. $ks->enddate);
        $row ++;
        $sheet->setCellValue('A' . $row, '序号');
        $sheet->setCellValue('B' . $row, '班级');
        $sheet->setCellValue('C' . $row, '姓名');
        $sheet->setCellValue('D' . $row, '性别');
        $i = 4;  # 跳过上面4列
        foreach ($subject_id as $key => $value) {
            $sheet->setCellValue($colname[$i] . $row, $value['title']);
            $i ++;
            foreach ($tjxm_left as $tjxml_k => $tjxml_v) {
                $sheet->setCellValue($colname[$i] . $row, $value['title'] . $tjxml_v['title']);
                $i ++;
            }
        }

        $sheet->setCellValue($colname [$i]. '3', '平均分');
        $i ++;
        $sheet->setCellValue($colname[$i] . '3', '总分');
        $left_i = $i;
        $sheet->mergeCells('A1:' . $colname[$i] . '1');   # 合并标题行
        $sheet->mergeCells('A2:' . $colname[$i] . '2');   # 合并时间行
        $col_null = 3;  # 统计结果与成绩列表空两列
        $i = $i + $col_null;

        $sheet->setCellValue($colname[$i] . $row, '项目');
        $row_temp = $row + 1;
        foreach ($tjxm_right as $tjxmr_k => $tjxmr_v) {
            $sheet->setCellValue($colname[$i] . $row_temp, $tjxmr_v['title']);
            $row_temp++;
        }
        $i ++;
        foreach ($subject_id as $key => $value) {
            $sheet->setCellValue($colname[$i] . $row, $value['title']);
            $i ++;
        }
        $right_i = $i;

        // 循环写出个人信息和成绩
        $i = 0;
        $row++;
        $row_temp = $row;
        foreach ($chengjiinfo as $key => $value) {
            $i = 0;
            // 表格赋值
            $sheet->setCellValue($colname[$i] . $row_temp, $row_temp - 3);
            $i++;
            $sheet->setCellValue($colname[$i] . $row_temp, $value['banji_title']);
            $i++;
            $sheet->setCellValue($colname[$i] . $row_temp, $value['student_xingming']);
            $i++;
            $sheet->setCellValue($colname[$i] . $row_temp, $value['sex']);
            $i++;
            foreach ($subject_id as $k => $val) {
                if(isset($value[$val['lieming'] . 'defen']))
                {
                    $sheet->setCellValue($colname[$i] . $row_temp, $value[$val['lieming'] . 'defen']);
                    $i++;
                    foreach ($tjxm_left as $tjxml_k => $tjxml_v) {
                        $sheet->setCellValue($colname[$i] . $row_temp, $value[$val['lieming'] . $tjxml_v['biaoshi']]);
                        $i++;
                    }
                }else{
                    $i++;
                }
            }
            $sheet->setCellValue($colname[$i] . $row_temp, $value['avg']);
            $i++;
            $sheet->setCellValue($colname[$i] . $row_temp, $value['sum']);
            $row_temp++;
        }

        // 表格格式
        $styleArrayJZ = [  # 居中
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, //垂直居中
            ],
        ];
        $sheet->getStyle('A1:'.$colname[$i] . $row_temp)->applyFromArray($styleArrayJZ);
        // 考试时间居左 表格格式
        $styleArrayJZ = [  # 居左
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $sheet->getStyle('A2')->applyFromArray($styleArrayJZ);
        $styleArrayBK = [ # 加边框
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $sheet->getStyle('A3:'.$colname[$left_i] . ($row_temp - 1))->applyFromArray($styleArrayBK);

        $tj = new \app\chengji\model\Tongji;

        $temp = $tj->tongjiSubject($chengjiinfo, $subject_id);

        // 左侧开始列
        $i = $left_i + $col_null ;
        $col_r = $i;

        // 循环写出统计结果
        foreach ($subject_id as $key => $value) {
            $i ++;
            $row_temp = $row;
            $chengji_cnt = $temp['cj'][$value['lieming']]['chengji_cnt'];
            if ($chengji_cnt > 0 ) {
                $temp['cj'][$value['lieming']]['youxiulv'] = round($temp['cj'][$value['lieming']]['youxiu'] / $chengji_cnt * 100, 2);
                $temp['cj'][$value['lieming']]['jigelv'] = round($temp['cj'][$value['lieming']]['jige'] / $chengji_cnt * 100, 2);
            } else {
                $temp['cj'][$value['lieming']]['youxiulv'] = 0;
                $temp['cj'][$value['lieming']]['jigelv'] = 0;
            }
            foreach ($tjxm_right as $tjxmr_k => $tjxmr_v) {
                $sheet->setCellValue($colname[$i] . $row_temp, $temp['cj'][$value['lieming']][$tjxmr_v['biaoshi']]);
                $row_temp++;
            }
        }


        $sheet->setCellValue($colname[$col_r] . $row_temp, '总平均分');
        $sheet->setCellValue($colname[$col_r + 1] . $row_temp, $temp['cj']['all']['avg']);
        $sheet->mergeCells($colname[$col_r + 1] . $row_temp . ':' . $colname[$i] . $row_temp);
        $row_temp++;
        $sheet->setCellValue($colname[$col_r] . $row_temp, '全科及格率%');
        $sheet->setCellValue($colname[$col_r + 1] . $row_temp, $temp['cj']['all']['jigelv']);
        $sheet->mergeCells($colname[$col_r + 1] . $row_temp . ':' . $colname[$i] . $row_temp);
        $sheet->getStyle($colname[$col_r] . ($row - 1) . ':' . $colname[$i] . $row_temp)->applyFromArray($styleArrayJZ);
        $sheet->getStyle($colname[$col_r] . ($row - 1) . ':' . $colname[$i] . $row_temp)->applyFromArray($styleArrayBK);


        $sheet->getStyle('A1')->getFont()->setBold(true)->setName('宋体')->setSize(16); # 修改标题字号
        $sheet->getDefaultRowDimension()->setRowHeight(22.5); # 设置行高
        $sheet->getRowDimension('1')->setRowHeight(25);
        $sheet->getDefaultColumnDimension()->setWidth(9); # 设置列宽
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension($colname[$i])->setWidth(13);

        // 页面设置
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageMargins()->setTop(0.8); # 页边距
        $sheet->getPageMargins()->setRight(0.2);
        $sheet->getPageMargins()->setLeft(0.2);
        $sheet->getPageMargins()->setBottom(0.8);
        $sheet->getPageSetup()->setHorizontalCentered(true); # 打印居中
        $sheet->getPageSetup()->setVerticalCentered(false);

        // 保存文件
        $filename = $tabletitle.date('ymdHis').'.xlsx';
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


    // 下载成绩表格
    public function dwChengjitiao($kaoshi_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '成绩下载'
            ,'butname' => '下载'
            ,'formpost' => 'POST'
            ,'url' => '/chengji/index/dwcjtiaoxlsx'
            ,'kaoshi_id' => $kaoshi_id
        );

        // 获取参加考试的学校和年级
        $cy = new \app\kaohao\model\SearchCanYu;
        $src['kaoshi_id'] = $kaoshi_id;
        $list['school_id'] = $cy->school($src);
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $list['nianji'] = $ksset->srcGrade($src);

        // 模板赋值
        $this->view->assign('list', $list);
        return $this->view->fetch('dw_chengji');
    }


    //生成学生成绩条表格
    public function dwchengjitiaoxlsx()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => '1'
                ,'banji_id' => array()
                ,'school_id'
                ,'ruxuenian'
                ,'subject_id'
            ], 'POST');
        // 用户职务判断
        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];
        if(count($src['banji_id']) > 0)        # 如果没有获取到班级id,则查询班级id
        {
            $src['auth']['banji_id'] = array_intersect($src['auth']['banji_id'], $src['banji_id']);
        }else{
            $this->error('哎呀~没有足够权限！', '/login/err');
        }

        // 获取要下载成绩的学校和年级信息
        $school = new \app\system\model\School;
        $schoolname = $school->where('id', $src['school_id'])->value('jiancheng');

        // 实例化验证模型
        $validate = new \app\chengji\validate\Cjdownload;
        // 验证表单数据
        $result = $validate->check($src);
        $msg = $validate->getError();

        // 如果验证不通过则停止保存
        if (!$result) {
            $this->error($msg);
        }

        // 获取参与考试的班级
        ob_start();
        $more = new \app\kaohao\model\SearchMore;
        $cy = new \app\kaohao\model\SearchCanYu;
        $src['all'] = true;
        $chengjiinfo = $more->srcChengjiList($src);
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $subject_id = $ksset->srcSubject($src);
        $onestucj = new \app\tools\model\OneStudentChengji;

        $tj = new \app\chengji\model\Tongji;
        $srcAll = [
            'kaoshi_id' => $src['kaoshi_id'],
            'ruxuenian' => $src['ruxuenian']
        ];
        $srcAll['banji_id']= array_column($cy->class($srcAll), 'id');
        $src['all'] = true;
        $chengjiinfo = $more->srcChengjiList($src);
        $njtj = $tj->tongjiSubject($chengjiinfo, $subject_id);

        // 获取考试标题
        $ks = new \app\kaoshi\model\Kaoshi;
        $ks = $ks->where('id', $src['kaoshi_id'])
                ->field('id, title, bfdate, enddate')
                ->find();

        // 获取考试年级名称
        $njlist = \app\facade\Tools::nianJiNameList($ks->getData('bfdate'));
        $nianji = $njlist[$src['ruxuenian']];
        $tabletitle = $ks->title . ' ' . $schoolname . ' ' . $nianji . ' ' . '学生成绩条';

        $cjDengji = true; # 开启成绩等级状态
        $sys = new \app\system\model\SystemBase;
        $sysInfo = $sys::sysInfo();
        if($sysInfo->studefen === 0){
            $cjDengji = false; # 开启成绩等级状态
        }

        $mf = array(); # 本次考试各学科分数线
        foreach ($subject_id as $mf_k => $mf_v) {
            $mf[$mf_v['id']]['youxiu'] = $mf_v['fenshuxian']['youxiu'];
            $mf[$mf_v['id']]['jige'] = $mf_v['fenshuxian']['jige'];
        }

        $tjxm_stu = src_tjxm(12211);   # 统计项目
        $tjxm_bj = src_tjxm(12212);   # 统计项目
        $colname = excel_column_name();

        // set_time_limit(0);
        // 创建表格
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $thistime = date("Y-m-d h:i:sa");
        // 设置文档属性
        $spreadsheet->getProperties()
            ->setCreator("码蚁成绩管理系统")    //作者
            ->setTitle("码蚁成绩管理")  //标题
            ->setLastModifiedBy(session('username')) //最后修改者
            ->setDescription("该表格由" . session('username') . session('id' ) ."于" . $thistime . "在码蚁成绩管理系统中下载，只作为内部交流材料,不允许外泄。")  //描述
            ->setKeywords("码蚁 成绩管理") //关键字
            ->setCategory("成绩管理"); //分类

        $row = 1;   # 定义从 $row 行开始写入数据
        $rows = count($subject_id);  # 定义学生信息列要合并的行数

        // 给单元格加边框
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, //垂直居中
            ],
        ];

        // 从这里开始循环写入学生成绩
        foreach ($chengjiinfo as $key => $value) {
            // 写入学生信息
            $sheet->setCellValue('A' . $row, '学校');
            $sheet->mergeCells('A' . ($row + 1).':' . 'A' . ($row + $rows)); # 输入学科名
            $sheet->setCellValue('A' . ($row + 1), $schoolname);
            $sheet->setCellValue('B' . $row, '班级');
            $sheet->mergeCells('B' . ($row + 1) . ':' . 'B' . ($row + $rows)); # 输入学科名
            $sheet->getStyle('B' . ($row + 1))->getAlignment()->setWrapText(true);
            $sheet->setCellValue('B' . ($row + 1), $value['banji_title']);
            $sheet->setCellValue('C' . $row, '姓名');
            $sheet->mergeCells('C' . ($row + 1) . ':' . 'C' . ($row + $rows)); # 输入学科名
            $sheet->setCellValue('C' . ($row + 1), $this->hideName($value['student_xingming']));
            $sheet->setCellValue('D' . $row, '学科');
            $sheet->setCellValue('E' . $row, '成绩');
            $col = 5;
            foreach ($tjxm_stu as $tjxmstu_k => $tjxmstu_v) {
                $sheet->setCellValue($colname[$col] . $row, $tjxmstu_v['title']);
                $col++;
            }
            foreach ($tjxm_bj as $tjxmbf_k => $tjxmbf_v) {
                $sheet->setCellValue($colname[$col] . $row, $tjxmbf_v['title']);
                $col++;
            }
            $row = $row + 1 ;

            // 写入成绩
            foreach ($subject_id as $k => $val) {
                $xkcnt = $njtj['cj'][$val['lieming']]['chengji_cnt'];
                if ($xkcnt > 0 ) {
                    $youxiulv = round($njtj['cj'][$val['lieming']]['youxiu'] / $xkcnt * 100, 2);
                    $jigelv = round($njtj['cj'][$val['lieming']]['jige'] / $xkcnt * 100, 2);
                } else {
                    $youxiulv = 0;
                    $jigelv = 0;
                }
                $sheet->setCellValue('D' . ($row + $k), $val['title']);
                $df = '';

                if($cjDengji === false)
                {
                    $df = $onestucj->toDengji($mf[$val['id']], $value[$val['lieming'] . 'defen']);
                }else{
                    $df = $value[$val['lieming'] . 'defen'];
                }
                $sheet->setCellValue('E' . ($row + $k), $df);   # 得分

                $col = 5;

                foreach ($tjxm_stu as $tjxmstu_k => $tjxmstu_v) {
                    $sheet->setCellValue($colname[$col] . ($row + $k), $value[$val['lieming'] . $tjxmstu_v['biaoshi']]);
                    $col++;
                }
                foreach ($tjxm_bj as $tjxmbj_k => $tjxmbj_v) {
                    $sheet->setCellValue($colname[$col] . ($row + $k), $njtj['cj'][$val['lieming']][$tjxmbj_v['biaoshi']]);
                    $col++;
                }
            }

            // 设置格式
            $sheet->getStyle('A' . ($row - 1) . ':' . $colname[$col - 1] . ($row + $rows - 1))->applyFromArray($styleArray);
            $row = $row + $rows + 1 ;
        }

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


    // 学生成绩录入信息
    public function readAdd($kaohao)
    {

        // 设置要给模板赋值的信息
        $list['webtitle'] = '查看成绩录入信息';
        $list['kaohao'] = $kaohao;
        $list['dataurl'] = '/chengji/index/dataadd';

        // 模板赋值
        $this->view->assign('list',$list);

        // 渲染模板
        return $this->view->fetch();
    }


    // ajax获取学生成绩录入信息
    public function ajaxaddinfo()
    {
        // 获取参数
        $src = $this->request
                ->only([
                    'page'=>'1',
                    'limit'=>'10',
                    'field'=>'subject_id',
                    'order'=>'desc',
                    'kaohao'=>''
                ],'POST');

        $chengji = new Chengji;

        $data = $chengji
                ->where('kaohao_id',$src['kaohao'])
                ->field('id, kaohao_id, subject_id, user_id, defen, update_time')
                ->order([$src['field']=>$src['order']])
                ->with([
                    'subjectName' => function ($query) {
                        $query->field('id,title');
                    },
                    'cjAdmin' => function ($query) {
                        $query->field('id, xingming, school_id')
                        ->with([
                            'adSchool' => function ($q) {
                                $q->field('id, jiancheng');
                            }
                        ]);
                    }
                ])
                ->append(['userInfo'])
                ->select();

        $cnt = count($data);
        // 获取当前页数据
        $limit_start = $src['page'] * $src['limit'] - $src['limit'];
        $limit_length = $src['limit'];
        $data = $data->slice($limit_start,$limit_length);

        // 重组返回内容
        $data = [
            'code'=> 0 , // ajax请求次数，作为标识符
            'msg'=>"",  // 获取到的结果数(每页显示数量)
            'count'=>$cnt, // 符合条件的总数据量
            'data'=>$data, //获取到的数据结果
        ];

        return json($data);
    }


    // 隐藏学生全名
    private function hideName($str)
    {
        $arr = $this->mb_str_split($str);
        $cnt = count($arr);
        if ($cnt <= 0) {
            $newStr = '***';
        } else if ($cnt == 1) {
            $newStr = reset($arr) . '**';
        } else if ($cnt ==2 or $cnt ==3) {
            $newStr = reset($arr) . '*' . end($arr);
        }else if ($cnt == 4){
            $newStr = reset($arr) . next($arr) . '*' . end($arr);
        } else {
            $l = round($cnt * 0.3,0);
            $newStr = '';
            for($i=0; $i<$l; $i++)
            {
                $newStr = $newStr . $arr[$i];
                $i++;
            }
            $newStr = $newStr . '*';
            for ($i=$cnt - $l; $i < $cnt; $i++) {
                $newStr = $newStr . $arr[$i];
            }
        }
        return $newStr;
    }

    private function mb_str_split($str,$split_length=1,$charset="UTF-8"){
      if(func_num_args()==1){
        return preg_split('/(?<!^)(?!$)/u', $str);
      }
      if($split_length<1)return false;
      $len = mb_strlen($str, $charset);
      $arr = array();
      for($i=0;$i<$len;$i+=$split_length){
        $s = mb_substr($str, $i, $split_length, $charset);
        $arr[] = $s;
      }
      return $arr;
    }

}
