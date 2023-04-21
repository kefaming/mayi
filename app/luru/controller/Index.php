<?php
// 引用控制器基类
namespace app\luru\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
use app\chengji\model\Chengji;
use app\teach\model\Subject;

// 引用数据模型类
use app\kaoshi\model\Kaoshi as KS;
use app\kaohao\model\SearchMore as srcMore;
use app\kaohao\model\SearchCanYu as srcCy;
use app\kaoshi\model\KaoshiSet as ksset;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Index extends AdminBase
{
    // 成绩列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '已录列表';
        $list['dataurl'] = '/luru/index/data';
        $list['status'] = '/chengji/index/status';

        // 获取学科列表
        $sbj = new \app\teach\model\Subject;
        $list['subject_id'] = $sbj->where('kaoshi', 1)
            ->where('status', 1)
            ->field('id, title, jiancheng')
            ->select();

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 获取本人录入成绩信息
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page'
                ,'limit'
                ,'field' => 'update_time'
                ,'order' => 'desc'
                ,'subject_id' => ''
                ,'searchval'
                ,'user_id' => session('user_id')
                ,'all' => false
            ], 'POST');

        // 根据条件查询数据
        $cj = new Chengji;
        $data = $cj->searchLuru($src);
        $src['all'] = true;
        $cnt = count($cj->searchLuru($src));
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 使用二维码录入成绩
    public function malu()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '扫码录成绩'
            ,'butname' => '录入'
            ,'formpost' => 'PUT'
            ,'url' => '/luru/index/malu'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 保存使用二维码录入的成绩
    public function malusave()
    {
        // 获取表单数据
        $list = $this->request->only([
            'kaohao_id'
            ,'subject_id'
            ,'nianji'
            ,'defen'
        ], 'POST');

        // 判断考试状态
        $kh = new \app\kaohao\model\Kaohao;
        $khInfo = $kh->where('id', $list['kaohao_id'])->find();
        event('kslu', $khInfo->kaoshi_id);

        // halt($list, $khInfo->toArray());

        $src = [
            'kaoshi_id' => $khInfo->kaoshi_id
            ,'banji_id' => $khInfo->banji_id
            ,'subject_id' => $list['subject_id']
        ];
        $auth = event('lrfg', $src);
        $auth = $auth[0];

        if($auth === false)
        {
            $data = ['msg' => '权限不足。', 'val' => 0];
            return json($data);
        }

        // 获取本学科满分
        $list['ruxuenian'] = $list['nianji'];
        $list['kaoshi_id'] = $khInfo->kaoshi_id;
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $subject = $ksset->srcSubject($list);

        if (count($subject) > 0) {
            $manfen = $subject[0]['fenshuxian']['manfen'];
        } else {
            $manfen = "";
        }

        // 成绩验证
        $mfyz = manfenvalidate($list['defen'], $manfen);
        if ($mfyz['val'] == 0) {
            return json($mfyz);
        }

        // 保存成绩
        $cjone = Chengji::withTrashed()
            ->where('subject_id', $list['subject_id'])
            ->where('kaohao_id', $list['kaohao_id'])
            ->find();

        // 如果存在成绩则更新，不存在则添加
        if ($cjone) {
            // 判断记录是否被删除
            if ($cjone->delete_time > 0) {
                $cjone->restore();
            }

            if ($cjone->defen == $list['defen']) {
                $data = ['msg' => '与原成绩相同，不需要修改。', 'val' => 0];
                return json($data);
            }

            $cjone->defen = $list['defen'];
            $cjone->user_id = session('user_id');
            $cjone->defenlv = $list['defen'] / $manfen * 100;
            $data = $cjone->save();
        } else {
            $data = [
                'kaohao_id' => $list['kaohao_id']
                ,'subject_id' => $list['subject_id']
                ,'user_id' => session('user_id')
                ,'defen' => $list['defen']
                ,'defenlv' => $list['defen'] / $manfen * 100
            ];
            $data = Chengji::create($data);
        }

        $data ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        return json($data);
    }


    // 学生成绩表中修改成绩
    public function update($id)  #id为考号ID
    {
        // 获取表单数据
        $list = $this->request->only([
            'colname'
            ,'newdefen'
        ] , 'POST');
        $list['kaohao_id'] = $id;

        // 判断考试结束时间是否已过
        $kh = new \app\kaohao\model\Kaohao;
        $khInfo = $kh
            ->where('id', $list['kaohao_id'])
            ->find();
        event('kslu', $khInfo->kaoshi_id);

        // 获取学科id
        $subject = new \app\teach\model\Subject;
        $subject_id = $subject->where('lieming', $list['colname'])->value('id');

        $src = [
            'kaoshi_id' => $khInfo->kaoshi_id
            ,'banji_id' => $khInfo->banji_id
            ,'subject_id' => $subject_id
        ];
        $auth = event('lrfg', $src);
        $auth = $auth[0];

        if($auth === false)
        {
            $data = ['msg' => '权限不足。', 'val' => 0];
            return json($data);
        }

        // 根据考号获取学生年在年级及考试ID
        $khinfo = $kh->where('id', $id)->find();
        // 获取本学科满分
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $khinfo->kaoshi_id
            ,'subject_id' => $subject_id
            ,'ruxuenian' => $khinfo->ruxuenian
        ];
        $subject = $ksset->srcSubject($src);
        if (count($subject)>0) {
            $manfen = $subject[0]['fenshuxian']['manfen'];
        } else {
            $manfen = "";
        }

        // 成绩验证
        $mfyz = manfenvalidate($list['newdefen'], $manfen);
        if ($mfyz['val'] == 0) {
            return json($mfyz);
        }

        // 更新成绩
        $cjone = Chengji::withTrashed()
            ->where('kaohao_id', $list['kaohao_id'])
            ->where('subject_id', $subject_id)
            ->find();

        // 如果存在成绩则更新，不存在则添加
        if ($cjone) {
            // 判断记录是否被删除
            if ($cjone->delete_time > 1) {
                $cjone->restore();
            }

            if ($cjone->defen == $list['newdefen']) {
                $data = ['msg' => '与原成绩相同，不需要修改。', 'val' => 1];
                return json($data);
            }

            $cjone->defen = $list['newdefen'];
            $cjone->defenlv = $list['newdefen'] / $manfen * 100;
            $cjone->user_id = session('user_id');
            $data = $cjone->save();
        } else {
            $data = [
                'kaohao_id' => $list['kaohao_id']
                ,'subject_id' => $subject_id
                ,'user_id' => session('user_id')
                ,'defen' => $list['newdefen']
                ,'defenlv' => $list['newdefen'] / $manfen * 100
            ];
            $data = Chengji::create($data);
        }

        // 判断返回内容
        $data  ? $data = ['msg' => '录入成功','val' => 1]
            : $data = ['msg' => '数据处理错误','val' => 0];

        // 返回更新结果
        return json($data);
    }


    // 根据考号获取学生信息
    public function read()
    {
        // 获取表单数据
        $val = input('post.val');
        // 实例化系统设置类
        $val = \app\facade\Tools::decrypt($val, 'dlbz');
        $yz = strstr($val, '|');
        if($yz == false)
        {
            $cjlist = array();
            return json($cjlist);
        }
        $list = explode('|', $val);
        $id = $list[0];
        $subject_id = $list[1];

        $khSrc = new \app\kaohao\model\SearchOne;
        $cjlist = $khSrc->srcOneSubjectChengji($id, $subject_id);
        $data = [
            "msg" => "获取成功"
            ,"val" => 1
            ,"data" => $cjlist
        ];
        // 获取列名
        return json($data);
    }


    // 表格录入成绩上传页面
    public function biaolu()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '表格录入'
            ,'butname' => '下载'
            ,'formpost' => 'POST'
            ,'url' => '/luru/index/dwcaiji'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 保存表格批量上传的成绩
    public function saveAll()
    {
        // 获取表单数据
        $list = $this->request->only([
            'url'
        ], 'POST');

        // 读取表格数据
        $fengefu = DIRECTORY_SEPARATOR;
        $cjinfo = \app\facade\File::readXls(public_path() . 'uploads' . $fengefu . $list['url']);

        $kaoshi_id = $cjinfo[1][0];  #获取考号
        $nianji = $cjinfo[1][1];  #获取年级

        if($kaoshi_id == null
            || $nianji==null
            || $cjinfo[2][0] != '序号'
            || $cjinfo[2][1] != '编号'
            || $cjinfo[2][2] != '班级'
            || $cjinfo[2][3] != '学号'
            || $cjinfo[2][4] != '姓名') {
            $data = ['msg' => '请使用模板上传', 'val' => 0];
            return json($data);
        }
        // 判断考试状态
        event('kslu', $kaoshi_id);

        // 删除空单元格得到学科列名数组
        array_splice($cjinfo[1], 0, 4);
        $xk = $cjinfo[1];
        // 删除成绩采集表无用的标题行得到成绩数组
        array_splice($cjinfo,0,3);

        $cjinfo = array_filter($cjinfo, function ($cjTemp) {
            return $cjTemp[1] != "";
        });

        // 查询考试信息
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'ruxuenian' => $nianji
            ,'subject_id' => $xk
        ];
        $sbj = $ksset->srcSubject($src);
        $subject = array();
        foreach ($sbj as $key => $value) {
            $key = array_search($value['id'], $xk);
            if (is_numeric($key)) {
                $subject[$key] = $value;
            }
        }

        $user_id = session('user_id');   # 获取用户id
        $data = array();
        $md5 = new \app\facade\Tools;
        $temp_banji = "";
        $temp_subject = "";
        $kh = new \app\kaohao\model\Kaohao;
        $yzjg = false;
        $cnt = 0;

        // 重新组合数组
        foreach ($subject as $key => $value) {
            # code...
            foreach ($cjinfo as $k => $val) {
                // 解密考号ID
                $temp_id = $md5::decrypt($val[1], 'dlbz');
                // 判断权限
                if ($temp_banji != $val[2] || $temp_subject != $value['id']) {
                    $src = [
                        'kaoshi_id'
                        ,'banji_id'
                        ,'subject_id'
                    ];
                    $khInfo = $kh->where('id', $temp_id)->find();
                    if ($khInfo) {
                        $src = [
                            'kaoshi_id' => $khInfo->kaoshi_id
                            ,'banji_id' => $khInfo->banji_id
                            ,'subject_id' => $value['id']
                        ];
                    }
                    $auth = event('lrfg', $src);
                    $auth = $auth[0];
                }
                $temp_banji = $val[2];
                $temp_subject = $value['id'];

                if($auth === false)
                {
                    continue;
                }

                $defen = $val[$key + 4];    # 当前学生当前学科成绩
                // 如果不存在值，跳过这次循环
                if ($defen === null) {
                    continue;
                }

                // 验证成绩格式，如果不对则跳过
                if (isset($value['fenshuxian']['manfen'])) {
                    $manfen = $value['fenshuxian']['manfen'];
                } else {
                    $manfen = "";
                }
                $mfyz = manfenvalidate($defen, $manfen);
                if ($mfyz['val'] == 0) {
                    continue;
                }

                // 添加或更新数据
                $cjone = Chengji::withTrashed()
                    ->where('kaohao_id', $temp_id)
                    ->where('subject_id', $value['id'])
                    ->find();
                // 判断成绩是否存在
                if ($cjone) {
                    // 如果存在则更新记录
                    if ($cjone->defen != $defen || $cjone->delete_time > 1) {
                        $cjone->restore();
                        $cjone->defen = $defen;
                        $cjone->defenlv = $defen / $manfen * 100;
                        $cjone->user_id = $user_id;
                        $cjone->save();
                    }
                } else {
                    // 如果不存在则新增记录
                    $data = [
                        'kaohao_id' => $temp_id
                        ,'subject_id' => $value['id']
                        ,'user_id' => $user_id
                        ,'defen' => $defen
                        ,'defenlv' => $defen / $manfen * 100
                    ];
                    Chengji::create($data);
                }
                $cnt ++;
            }
        }

        // 判断成绩更新结果
        $data = ['msg' => '成绩导入成功' . $cnt . '个成绩', 'val' => 1];
        ob_flush();
        flush();
        // 返回成绩结果
        return json($data);
    }


    // 获取参考名单
    public function dwcaiji()
    {
        // 获取表单数据
        $list = request()->only([
            'banji_id' => array()
            ,'ruxuenian'
            ,'kaoshi_id'
            ,'subject_id' => array()
        ], 'POST');
        event('kslu', $list['kaoshi_id']);

        $kaoshi_id = $list['kaoshi_id'];
        $banji_id = $list['banji_id'];
        $subject_id = $list['subject_id'];

        // 验证表单数据
        $validate = new \app\kaoshi\validate\Biaoqian;
        $result = $validate->scene('caiji')->check($list);
        $msg = $validate->getError();
        if(!$result){
            $this->error($msg);
        }

        ob_start();
        $ks = new KS();
        $kslist = $ks::where('id', $kaoshi_id)
                    ->field('id, title')
                    ->find();
        // 获取参加考试学科
        $ksset = new ksset();
        $ksSubject = $ksset->srcSubject($list);

        // 查询考号
        $srcMore = new srcMore();
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'banji_id' => $banji_id
        ];
        $kaohao = $srcMore->srcBanjiKaohao($src);

        // 获取电子表格列名
        $lieming = excel_column_name();
        // 创建表格
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $thistime = date("Y-m-d h:i:sa");
        // 设置文档属性
        $spreadsheet->getProperties()
            ->setCreator("尚码成绩管理系统")    //作者
            ->setTitle("尚码成绩管理")  //标题
            ->setLastModifiedBy(session('username')) //最后修改者
            ->setDescription("该表格由" . session('user_id') . "于" . $thistime . "在尚码成绩管理系统中下载，只作为内部交流材料,不允许外泄。")  //描述
            ->setKeywords("尚码 成绩管理") //关键字
            ->setCategory("成绩管理"); //分类

        // 设置表格标题与表头信息
        $sheet->setCellValue('A1',$kslist->title . ' 成绩采集表');
        $sheet->setCellValue('A2', $kaoshi_id);
        $sheet->setCellValue('B2', $list['ruxuenian']);
        $sheet->setCellValue('A3', '序号'); # 表头
        $sheet->setCellValue('B3', '编号');
        $sheet->setCellValue('C3', '班级');
        $sheet->setCellValue('D3', '学号');
        $sheet->setCellValue('E3', '姓名');
        // 获取列数并合并和一行
        $col = $lieming[count($ksSubject) + 4];
        $sheet->mergeCells('A1:' . $col . '1');

         // 写入列名
        foreach ($ksSubject as $key => $value) {
            $sheet->setCellValue($lieming[$key + 5] . '3', $value['title']);
            $sheet->setCellValue($lieming[$key + 5] . '2', $value['id']);
        }
        // 隐藏第二行和第二列
        $sheet->getRowDimension('2')->setRowHeight(0);
        $sheet->getColumnDimension('B')->setWidth(0);
        $sheet->getColumnDimension('C')->setWidth(13);

        $md5 = new \app\facade\Tools;

        // 将学生信息循环写入表中
        $i = 4;
        foreach ($kaohao as $key=>$bj)
        {
            $bjkh = $bj->banjiKaohao->toArray();
            $bjkh = \app\facade\Tools::sortArrByManyField($bjkh, 'shoupin', SORT_ASC);
            foreach ($bjkh as $k => $kh) {
                $sheet->setCellValue('A' . $i, $i - 3);
                if(isset($kh['cjStudent']['xingming']))
                {
                    $sheet->setCellValue('E' . $i, $kh['cjStudent']['xingming']);
                }else{
                    $sheet->setCellValue('E' . $i, 'Null');
                }
                $sheet->setCellValue('B' . $i, $md5::encrypt((string)$kh['id'], 'dlbz'));
                $sheet->setCellValue('C' . $i, $bj['banjiTitle']);
                $sheet->setCellValue('D' . $i, $kh['cjStudent']['xuehao']);
                $i ++;
            }
        }

        // 居中
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, //垂直居中
            ],
        ];
        $sheet->getStyle('A1:' . $col . ($i - 1))->applyFromArray($styleArray);

        // 加边框
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $sheet->getStyle('A3:' . $col . ($i - 1))->applyFromArray($styleArray);
        // 修改标题字号
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setName('宋体')->setSize(11);
        // 设置行高
        $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        // 设置筛选
        $sheet->setAutoFilter('A3:' . $col . ($i - 1));

        // 保存文件
        $filename = $kslist->title . '成绩采集表' . date('ymdHis') . '.xlsx';
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



    // 标签下载
    public function biaoqian()
    {
        $ks = new \app\kaoshi\model\Kaoshi;
        // 获取参考年级
        $list['data'] = $ks::order(['id' => 'desc'])
                ->field('id, title')
                ->where('luru', 1)
                ->where('status', 1)
                ->select();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '表格录入'
            ,'butname' => '下载'
            ,'formpost' => 'POST'
            ,'url' => '/luru/index/biaoqianxls'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    //生成标签信息
    public function biaoqianXls()
    {
        // 获取表单数据
        $list = request()->only([
            'banji_id'=>array()
            ,'kaoshi_id'
            ,'subject_id'=>array()
        ], 'POST');
        event('kslu', $list['kaoshi_id']);
        $kaoshi_id = $list['kaoshi_id'];
        $banji_id = $list['banji_id'];
        $subject = $list['subject_id'];

        // 实例化验证模型
        $validate = new \app\kaoshi\validate\Biaoqian;
        $result = $validate->scene('biaoqian')->check($list);
        $msg = $validate->getError();
        if(!$result){
            $this->error($msg);
        }

        ob_start();
        $ks = new KS();
        $kslist = $ks::where('id', $kaoshi_id)
                    ->field('id, title')
                    ->find();
        // 获取参考学科
        $ksset = new ksset();
        $ksSubject = $ksset->srcSubject($list);

        // 查询考号
        $srcMore = new srcMore();
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'banji_id' => $banji_id
        ];
        $kaohao = $srcMore->srcBanjiKaohao($src);

        $thistime = date("Y-m-d h:i:sa");
        // 创建表格
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $thistime = date("Y-m-d h:i:sa");
        // 设置文档属性
        $spreadsheet->getProperties()
            ->setCreator("尚码成绩管理系统")    //作者
            ->setTitle("尚码成绩管理")  //标题
            ->setLastModifiedBy(session('username')) //最后修改者
            ->setDescription("该表格由" . session('username') . session('user_id') . "于" . $thistime . "在尚码成绩管理系统中下载，只作为内部交流材料,不允许外泄。")  //描述
            ->setKeywords("尚码 成绩管理") //关键字
            ->setCategory("成绩管理"); //分类

        // 设置表头信息
        $sheet->setCellValue('A1', '序号');
        $sheet->setCellValue('B1', '考号');
        $sheet->setCellValue('C1', '学校');
        $sheet->setCellValue('D1', '班级');
        $sheet->setCellValue('E1', '学科');
        $sheet->setCellValue('F1', '姓名');

        // 循环写出信息
        $i = 2;
        foreach ($kaohao as $key=>$bj)
        {
            foreach ($ksSubject as $ksbj => $sbj)
            {
                foreach ($bj->banjiKaohao as $kkh => $kh)
                {
                    // 表格赋值
                    $sheet->setCellValue('A' . $i, $i-1);
                    $stuKaohao = '';
                    $stuKaohao = \app\facade\Tools::encrypt($kh->id . '|' . $sbj['id'], 'dlbz');
                    $sheet->setCellValue('C' . $i, $bj->cjSchool->jiancheng);
                    $sheet->setCellValue('B' . $i, $stuKaohao);
                    $sheet->setCellValue('D' . $i, $bj->numBanjiTitle);
                    $sheet->setCellValue('E' . $i, $sbj['jiancheng']);
                    $sheet->setCellValue('F' . $i, $kh->cjStudent['xingming']);
                    $i++;
                }
            }
        }

        // 保存文件
        $filename = $kslist->title . ' 标签数据' . date('ymdHis') . '.xls';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        $writer->save('php://output');
        ob_flush();
        flush();
        exit();
    }


    // 标签下载
    public function online()
    {
        $ks = new \app\kaoshi\model\Kaoshi;
        // 获取参考年级
        $list['data'] = $ks::order(['id' => 'desc'])
                ->field('id, title')
                ->where('luru', 1)
                ->where('status', 1)
                ->select();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '在线录入选择'
            ,'butname' => '打开学生名单'
            ,'formpost' => 'POST'
            ,'url' => '/luru/index/onlineedit'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 在线编辑成绩
    public function onlineedit()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id'
                ,'banji_id' => array()
                ,'subject_id' => array()
            ], 'POST');
        event('kslu', $src['kaoshi_id']);

        // 设置要给模板赋值的信息
        $list['webtitle'] = '学生成绩列表';

        // 获取参加考试的年级和学科
        $sbj = new \app\teach\model\Subject;
        $sbjList = $sbj->where('id', 'in', $src['subject_id'])
                    ->field('id, title, lieming')
                    ->select();
        $list['dataurl'] = '/luru/index/onlinedata';
        $list['set']['src'] = $src;
        $list['set']['subjectList'] = $sbjList;
        $list['backurl'] = '/luru/index/online';

        // 模板赋值
        $this->view->assign('list', $list);
        return $this->view->fetch();
    }


    // 获取在线编辑成绩数据
    public function ajaxDataOnline()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page'
                ,'limit'
                ,'field' => 'student_xingming'
                ,'order' => 'asc'
                ,'kaoshi_id'
                ,'banji_id' => array()
                ,'subject_id' => array()
                ,'all' => false
            ], 'POST');

        // 实例化并查询成绩
        $cj = new \app\kaohao\model\SearchMore;
        $data = $cj->srcOnlineEdit($src);
        $src['all'] = true;
        $cnt =  count($cj->srcOnlineEdit($src));
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 统计已经录入成绩数量--选择页面
    public function yiluCnt()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '已录统计'
            ,'butname' => '打开'
            ,'formpost' => 'POST'
            ,'url' => '/luru/index/yilutable'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 统计已经录入成绩数量
    public function yiluCntTable()
    {
        // 获取参数
        $kaoshi_id = input('kaoshi_id');

        // 获取考试信息
        $ks = new \app\kaoshi\model\Kaoshi;
        $ksinfo = $ks->where('id', $kaoshi_id)
            ->field('id, title')
            ->find();

        // 获取参加考试的年级和学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        $list['nianji'] = $ksset->srcGrade($src);
        $list['subject_id'] = $ksset->srcSubject($src);

        // 获取参与学校
        if(count($list['nianji']) > 0)
        {
            $khSrc = new \app\kaohao\model\SearchCanYu;
            $src['ruxuenian'] = [$list['nianji'][0]['ruxuenian']];
            $src['kaoshi_id'] = $kaoshi_id;
            $list['school_id'] = $khSrc->school($src);
        }

        // 设置要给模板赋值的信息
        $list['webtitle'] = '各年级的班级成绩列表';
        $list['kaoshi_id'] = $kaoshi_id;
        $list['kaoshititle'] = $ksinfo->title;
        $list['dataurl'] = '/luru/index/datatj';
        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取年级成绩统计结果
    public function ajaxDatayltj()
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
                ,'field' => 'banji_id'
                ,'order' => 'asc'
            ], 'POST');

        // 统计成绩
        $btj = new \app\chengji\model\TongjiBj;
        $data = $btj->tjBanjiCnt($src);
        $data = reset_data($data, $src);

        return json($data);
    }
}
