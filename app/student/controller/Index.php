<?php

namespace app\student\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用学生数据模型类
use app\student\model\Student as STU;
// 引用phpspreadsheet类
use app\tools\controller\File;

class Index extends AdminBase
{
    // 显示学生列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '学生列表';
        $list['dataurl'] = '/student/index/data';
        $list['status'] = '/student/index/status';
        $list['kaoshi'] = '/student/index/kaoshi';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取学生信息列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'update_time'
                ,'order' => 'desc'
                ,'school_id' => array()
                ,'ruxuenian' => date('Y')
                ,'banji_id' => array()
                ,'searchval' => ''
            ], 'POST');

        $src['auth'] = event('mybanji', array('renke' => false));
        $src['auth'] = $src['auth'][0];

        // 根据条件查询数据
        $stu = new STU;
        $data = $stu->search($src);
        $src['all'] = true;
        $cnt = $stu->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 毕业学生列表
    public function byList()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '毕业学生';
        $list['dataurl'] = '/student/index/databy';
        $njlist = \app\facade\Tools::nianJiNameList(strtotime("-6 year"), 'num');
        $cnt = count($njlist);
        foreach ($njlist as $key => $value) {
            $list['njlist'][$value] = $value + $cnt;
        }

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取学生信息列表
    public function ajaxDataBy()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'update_time'
                ,'order' => 'desc'
                ,'school_id' => array()
                ,'ruxuenian' => array()
                ,'banji_id' => array()
                ,'searchval' => ''
            ], 'POST');

        $src['auth'] = event('mybanji', ['zuzhang'=>false, 'banzhuren' => false]);
        $src['auth'] = $src['auth'][0];

        // 根据条件查询数据
        $stu = new STU;
        $data = $stu->searchBy($src);
        $src['all'] = true;
        $cnt = $stu->searchBy($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 删除学生列表
    public function delList()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '异动学生';
        $list['dataurl'] = '/student/index/datadel';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取学生信息列表
    public function ajaxDataDel()
    {
        // 获取参数
        $src = $this->request
                ->only([
                    'page' => '1'
                    ,'limit' => '10'
                    ,'field' => 'update_time'
                    ,'order' => 'desc'
                    ,'school_id' => array()
                    ,'ruxuenian' => array()
                    ,'banji_id' => array()
                    ,'searchval' => ''
                ], 'POST');

        $src['auth'] = event('mybanji', array('banzhuren' => false, 'zuzhang' => false));
        $src['auth'] = $src['auth'][0];
        $src['auth']['banji_id'] = array_intersect($src['auth']['banji_id'], $src['auth']['banji_id']);

        // 实例化
        $stu = new STU;
        $data = $stu->searchDel($src);
        $src['all'] = true;
        $cnt = $stu->searchDel($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建学生
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加学生'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 保存信息
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'xingming'
            ,'sex'
            ,'shenfenzhenghao'
            ,'shengri'
            ,'ruxuenian'
            ,'banji_id'
            ,'kaoshi'
            ,'quanpin'
            ,'shoupin'
            ,'xuehao'
            ,'guoqi' => \app\facade\Tools::mima_guoqi()
        ], 'POST');

        // 验证表单数据
        $validate = new \app\student\validate\Student;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }
        // 权限判断
        $bjqx = event('mybanji', array());
        $bjqx = $bjqx[0];
        if ($bjqx['check'] == true) {
            if (!in_array($list['banji_id'], $bjqx['banji_id'])) {
                return json(['msg' => '权限不足', 'val' => 0]);
            }
        }

        $list['shenfenzhenghao'] = strtoupper($list['shenfenzhenghao']);
        // 大写转小写
        $list['quanpin'] =  trim(strtolower($list['quanpin']));
        $list['shoupin'] =  trim(strtolower($list['shoupin']));

        // 查询数据是否重复
        $chongfu = STU::withTrashed()
            ->where('shenfenzhenghao', $list['shenfenzhenghao'])
            ->find();
        // 保存或更新数据
        $stu = new STU;
        if($chongfu == Null)
        {
            $data = $stu::create($list);

        }else{
            if($chongfu->delete_time > 0)
            {
                $chongfu->restore();
            }
            $data = $stu::update($list, ['id' => $chongfu->id]);
        }

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改学生信息
    public function edit($id)
    {
        // 获取学生信息
        $list['data'] = STU::field('id, xingming, sex, shenfenzhenghao, shengri, banji_id, kaoshi, status, quanpin, shoupin, xuehao')
            ->with([
                    'stuBanji'=>function($query){
                        $query->field('id, ruxuenian, paixu, school_id')
                            ->append(['banTitle']);
                    }
                ])
            ->find($id);

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑学生'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/student/index/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新学生信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'xingming'
            ,'sex'
            ,'shengri'
            ,'shenfenzhenghao'
            ,'ruxuenian'
            ,'banji_id'
            ,'kaoshi'
            ,'quanpin'
            ,'shoupin'
            ,'xuehao'
        ], 'PUT');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\student\validate\Student;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg'=>$msg,'val'=>0]);;
        }

        // 大写转小写
        $list['quanpin'] =  trim(strtolower($list['quanpin']));
        $list['shoupin'] =  trim(strtolower($list['shoupin']));

        $stu = new STU();
        $stuinfo = $stu::withTrashed()
            ->where('shenfenzhenghao', $list['shenfenzhenghao'])
            ->where('id', '<>', $id)
            ->with([
                'stuBanji'
            ])
            ->find();

        if($stuinfo){
            return json([
                'msg' => '此身份证号与　' . $stuinfo->stuSchool->jiancheng . ':' . $stuinfo->xingming . '　重复。'
                ,'val' => 0
            ]);
        }

        // 更新数据
        $stu = new STU();
        $data = $stu::update($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 学生信息
    public function read($id)
    {
        // 查询教师信息
        $myInfo = STU::withTrashed()
            ->where('id', $id)
            ->with([
                'stuBanji' => function($query){
                    $query->field('id, school_id, ruxuenian, paixu')
                        ->with([
                            'glSchool' => function($q){
                                $q->field('id,title,jiancheng');
                            }
                        ])
                        ->append(['banjiTitle']);
                },
            ])
            ->find();
        // 设置页面标题
        $myInfo['webtitle'] = $myInfo->xingming . '－信息';

        // 模板赋值
        $this->view->assign('list', $myInfo);
        // 渲染模板
        return $this->view->fetch();
    }


    // 删除学生
    public function delete($id)
    {

        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = STU::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data=['msg'=>'删除成功','val'=>1] : $data=['msg'=>'数据处理错误','val'=>0];

        // 返回信息
        return json($data);
    }


    // 恢复删除
     public function reDel()
    {
        //  获取id变量
        $id = request()->post('id');
        $user = STU::onlyTrashed()->find($id);
        $data = $user->restore();

        // 根据更新结果设置返回提示信息
        $data ? $data=['msg'=>'恢复成功','val'=>1] : $data=['msg'=>'数据处理错误','val'=>0];

        // 返回信息
        return json($data);
    }


    // 设置学生状态
    public function setStatus()
    {

        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取学生信息
        $data = STU::where('id',$id)->update(['status'=>$value]);
        $data ? $data=['msg'=>'状态设置成功','val'=>1] : $data=['msg'=>'数据处理错误','val'=>0];

        // 返回信息
        return json($data);
    }


    // 设置学生状态
    public function setKaoshi()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取学生信息
        $data = STU::where('id',$id)->update(['kaoshi'=>$value]);
        $data ? $data=['msg'=>'状态设置成功','val'=>1] : $data=['msg'=>'数据处理错误','val'=>0];

        // 返回信息
        return json($data);
    }


    // 使用上传的表格进行校对，表格中不存在的数据删除
    public function createAll()
    {

        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'同步学生名单',
            'butname'=>'同步',
            'formpost'=>'POST',
            'url'=>'/student/index/saveall',
        );

        // 模板赋值
        $this->view->assign('list',$list);
        // 渲染
        return $this->view->fetch('create_all');
    }


    // 使用上传的表格进行校对，表格中不存在的数据删除
    public function saveAll()
    {
        // 获取表单数据
        $list = request()->only([
            'school_id'
            ,'url'
        ], 'POST');

        $fengefu = DIRECTORY_SEPARATOR;
        // 读取表格数据
        $stuinfo = \app\facade\File::readXls(public_path() . 'uploads' . $fengefu . $list['url']);
        if($stuinfo[0][2] != "序号" && $stuinfo[1][2] != "姓名" && $stuinfo[2][2] != "学号" && $stuinfo[3][2] != "身份证号")
        {
            \app\facade\OnLine::jump('/login/err', '请使用模板上传');
            return true;
        }

        // 同步学生信息并返回数据库中多的信息
        $stu = new STU;
        $delStuList = $stu->tongBu($stuinfo, $list['school_id']);

        // 获取学校名称
        $sch = new \app\system\model\School;
        $sch = $sch->where('id', $list['school_id'])->value('jiancheng');

        // 导出要删除的信息
        // 创建表格
        set_time_limit(0);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        // 设置表头信息
        $sheet->setCellValue('A1', $sch . '电子表格中不存在的学生名单');
        $sheet->setCellValue('A2', '此表中学生名单是系统中比上传名单中多的数据，是否删除请结合实际情况');
        $sheet->setCellValue('A3', '序号');
        $sheet->setCellValue('B3', 'ID');
        $sheet->setCellValue('C3', '班级');
        $sheet->setCellValue('D3', '姓名');
        $sheet->setCellValue('E3', '性别');

        // 定义数据起始行号
        $i = 4;
        foreach ($delStuList as $key => $val) {
            $j = $i + $key;
            $sheet->setCellValue('A' . $j, $key + 1);
            $sheet->setCellValue('B' . $j, $val['id']);
            $sheet->setCellValue('C' . $j, $val['banjiTitle']);
            $sheet->setCellValue('D' . $j, $val['xingming']);
            $sheet->setCellValue('E' . $j, $val['sex']);
        }

        // 保存文件
        $filename = $sch . '电子表格中不存在的学生名单' . date('ymdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        ob_flush();
        flush();
    }


    // 使用上传的表格进行校对，表格中不存在的数据删除
    public function deletes()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'根据表格删除学生信息',
            'butname'=>'删除',
            'formpost'=>'POST',
            'url'=>'/student/index/deleteall',
        );

        // 模板赋值
        $this->view->assign('list',$list);
        // 渲染
        return $this->view->fetch();
    }


    // 使用上传的表格进行校对，表格中不存在的数据删除
    public function deleteXlsx()
    {
        // 获取表单数据
        $list = request()->only([
            'url'
        ], 'POST');

        $fengefu = DIRECTORY_SEPARATOR;
        // 读取表中数据
        $stuinfo = \app\facade\File::readXls(public_path() . 'uploads' . $fengefu . $list['url']);

        // 判断表格是否正确
        if($stuinfo[0][2] != "序号" && $stuinfo[1][2] != "ID" && $stuinfo[2][2] != "班级" && $stuinfo[3][2] != "姓名")
        {
            $data = array('msg'=>'请使用模板上传','val'=>0,'url'=>null);
            return json($data);
        }

        // 整理数据
        array_splice($stuinfo,0,3);     # 去掉标题行
        $stuinfo = array_filter($stuinfo,function($q){
            return $q[1] != null && $q[2] != null && $q[3] != null; # 去掉有空值行
        });
        $stuids = array_column($stuinfo, 1); # 获取学生信息

        // 删除学生信息
        $stu = new STU;
        $data = $stu::destroy(function($query) use($stuids){
            $query->where('id', 'in', $stuids);
        });

        $data ? $data = ['msg' => '数据同步成功', 'val' => 1]
            : ['msg' => '数据同步失败', 'val' => 0];

        return json($data);
    }


    // 下载表格模板
    public function download()
    {
        $url = public_path() . 'uploads/student/student_template.xlsx';
        return download($url,'学生名单模板.xlsx');
    }


    // 更新一个学生的考号
    public function updateOneKaohao($id)
    {
        // 获取表单数据
        $list = request()->only([
            'kaohao'
        ], 'PUT');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\student\validate\Student;
        $result = $validate->scene('onekaohao')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg'=>$msg,'val'=>0]);;
        }

        // 更新数据
        $stu = new STU();
        $data = $stu::update($list, ['id' => $id]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '考号更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 根据学生姓名、首拼、全拼搜索学生信息
    public function srcStudent()
    {
        // 声明结果数组
        $data = array();
        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi' => 1
                ,'banji_id' => ''
                ,'searchval' => ''
                ,'page' => 1
                ,'limit' => 10
                ,'field' => 'id'
                ,'order' => 'desc'
            ],'POST');


        // 判断是否存在数据，如果没有数据则返回。
        if(strlen($src['searchval']) <= 0){
            $data = reset_data($data, 0);
            return json($data);
        }

        // 如果有数据则查询教师信息
        $stu = new STU();
        $list = $stu->searchStr($src);
        $src['all'] = true;
        $cnt = $stu->searchStr($src)->count();

        foreach ($list as $key => $value) {
            $data[] = [
                'id' => $value->id
                ,'xingming' => $value->xingming . '  ' . date('Y/m' , $value->getData('shengri'))
                ,'shengri'
            ];
        }

        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 重置密码
    public function resetpassword($id)
    {
        // 生成密码
        $md5 = new \WhiteHat101\Crypt\APR1_MD5;
        $password = $md5->hash('123456');

        $data = [
            'password' => $password
            ,'guoqi' => \app\facade\Tools::mima_guoqi()
        ];

        // 查询用户信息
        $data = STU::where('id', $id)->update($data);
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '密码已经重置为:<br>123456', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }



    // 查询用户名是否重复
    public function srcSfzh()
    {
        // 获取参数
        $srcfrom = $this->request
            ->only([
                'searchval' => ''
                ,'id'
            ], 'POST');
        $src = [
                'searchval' => ''
                ,'id' => ''
            ];
        $src = array_cover($srcfrom, $src);

        $stu = new STU();
        $list = $stu->where('shenfenzhenghao', $src['searchval'])
            ->find();

        // 根据更新结果设置返回提示信息
        $data = ['msg' => '身份证号已经存在！', 'val' => 0];
        if($list)
        {
            if($src['id'] > 0)
            {

                if($src['id'] == $list->id){
                    $data = ['msg' => '', 'val' => 1];
                }
            }
        }else{
           $data = ['msg' => '', 'val' => 1];
        }
        return json($data);
    }

}
