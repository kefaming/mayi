<?php

namespace app\admin\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用用户数据模型
use app\admin\model\Admin as AD;
// 引用加密类
use WhiteHat101\Crypt\APR1_MD5;
// 引用PhpSpreadsheet类
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends AdminBase
{
    // 管理员列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '管理员列表';
        $list['dataurl'] = '/admin/index/data';
        $list['status'] = '/admin/index/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取数据管理员数据
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'id'
                ,'order' => 'desc'
                ,'searchval' => ''
            ],'POST');

        // 实例化
        $ad = new AD;
        $data = $ad->search($src)
            ->visible([
                'id'
                ,'xingming'
                ,'sex'
                ,'shengri'
                ,'username'
                ,'adSchool' => ['jiancheng']
                ,'glGroup' => ['title']
                ,'phone'
                ,'thistime'
                ,'lasttime'
                ,'ip'
                ,'denglucishu'
                ,'status'
                ,'update_time'
            ]);
        $src['all'] = true;
        $cnt = $ad->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建用户信息
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加管理员'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
        );
        $list['data']['worktime'] = date('Y-m-d', time());

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 保存管理员
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'xingming'
            ,'quanpin'
            ,'shoupin'
            ,'username'
            ,'shengri'
            ,'sex'
            ,'phone'
            ,'school_id'
            ,'group_id'
            ,'zhiwu_id'
            ,'zhicheng_id'
            ,'xueli_id'
            ,'biye'
            ,'zhuanye'
            ,'worktime'
            ,'tuixiu'
            ,'guoqi' => \app\facade\Tools::mima_guoqi()
            ,'beizhu'
        ], 'POST');

        // 设置密码，默认为123456
        $md5 = new APR1_MD5();
        $list['password'] = $md5->hash('123456');
        $list['username'] = strtolower($list['username']);

        // 验证表单数据
        $validate = new \app\admin\validate\Admin;
        $result = $validate->scene('admincreate')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 实例化管理员数据模型类
        $admin = new AD();
        $admindata = $admin->create($list);
        $groupdata=$admindata->glGroup()->saveAll($list['group_id']);   # 更新中间表

        // 根据更新结果设置返回提示信息
        $admindata && $groupdata ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }

    // 修改信息
    public function edit($id)
    {
        $ad = new AD;
        // 获取用户信息
        $list['data'] = $ad
            ->searchOne($id);

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑管理员'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/admin/index/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新管理员信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'xingming'
            ,'quanpin'
            ,'shoupin'
            ,'username'
            ,'shengri'
            ,'sex'
            ,'phone'
            ,'school_id'
            ,'group_id' => array()
            ,'zhiwu_id'
            ,'zhicheng_id'
            ,'xueli_id'
            ,'biye'
            ,'zhuanye'
            ,'worktime'
            ,'tuixiu'
            ,'beizhu'
        ], 'PUT');
        $list['id'] = $id;

        $list['group_id'] = array_values($list['group_id']);

        // 验证表单数据
        $validate = new \app\admin\validate\Admin;
        $result = $validate->scene('adminedit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 更新管理员信息
        $admindata = AD::update($list);
        // 更新中间表
        $groupdata=$admindata->glGroup()->detach();
        $groupdata=$admindata->glGroup()->attach($list['group_id']);

        // 根据更新结果设置返回提示信息
        $admindata && $groupdata ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        // 启动事务
        \think\facade\Db::startTrans();
        try {
            AD::destroy($id);
            $aga = new \app\admin\model\AuthGroupAccess;
            $id = $aga->where('uid', 'in', $id)->column('id');
            $aga::destroy($id);
            \think\facade\Db::commit();
            $data = true;
        } catch (\Exception $e) {
            // 回滚事务
            \think\facade\Db::rollback();
            $data = false;
        }

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改管理员状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 更新管理员信息
        $data = AD::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 重置密码
    public function resetPassword($id)
    {
        // 生成密码
        $md5 = new APR1_MD5();
        $password = $md5->hash('123456');

        $data = [
            'password' => $password
            ,'guoqi' => \app\facade\Tools::mima_guoqi()
        ];

        // 查询用户信息
        $data = AD::where('id', $id)->update($data);
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '密码已经重置为:<br>123456', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 批量添加
    public function createAll()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '批量上传管理员信息'
            ,'butname' => '批传'
            ,'formpost' => 'POST'
            ,'url' => 'saveall'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 批量保存
    public function saveAll()
    {
        // 获取表单数据
        $list = request()->only([
            'school_id'
            ,'url'
        ], 'POST');

        // 实例化操作表格类
        $teacherinfo = \app\facade\File::readXls(public_path() . 'uploads/' . $list['url']);

        // 判断表格是否正确
        if("教师信息批量录入表" != $teacherinfo[0][0] || '姓名*' != $teacherinfo[2][1] || '帐号*' != $teacherinfo[2][2])
        {
            $this->error('请使用模板上传', '/login/err');
            return json($data);
        }

        $admin = new AD;
        $temp = $admin->createAll($teacherinfo, $list['school_id']);
        $data = ['msg' => '数据上传成功', 'val' => 1, 'data' => $temp];

        return json($data);
    }


    // 下载表格模板
    public function downloadXls()
    {
        $fengefu = DIRECTORY_SEPARATOR;
        $url = public_path() . 'uploads' . $fengefu . 'admin' . $fengefu . 'AdminInfo.xlsx';
        // 读取表格数据
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($url);
        $stuinfo = $spreadsheet->getActiveSheet();

        // 写入性别数据有效性
        $value = "男|1,女|0,未知|2";
        $this->youxiaoxingAll($stuinfo, 'D', $value);

        $category = new \app\system\model\Category;
        $src = [
            'p_id' => 107
            ,'order' => 'asc'
        ];
        // 写入行政职务
        $zw = $category->srcChild($src);
        $value = "";
        foreach ($zw as $key => $val) {
            if ($key == 0)
            {
                $value = $val->title . '|' . $val->id;
            } else {
                $value = $value . ',' . $val->title . '|' . $val->id;
            }
        }
        $this->youxiaoxingAll($stuinfo, 'H', $value);

        // 专业职务
        $src['p_id'] = 106;
        $zw = $category->srcChild($src);
        $value = "";
        foreach ($zw as $key => $val) {
            if ($key == 0)
            {
                $value = $val->title . '|' . $val->id;
            } else {
                $value = $value . ',' . $val->title . '|' . $val->id;
            }
        }
        $this->youxiaoxingAll($stuinfo, 'I', $value);

        // 学历
        $src['p_id'] = 105;
        $zw = $category->srcChild($src);
        $value = "";
        foreach ($zw as $key => $val) {
            if ($key == 0)
            {
                $value = $val->title . '|' . $val->id;
            } else {
                $value = $value . ',' . $val->title . '|' . $val->id;
            }
        }
        $this->youxiaoxingAll($stuinfo, 'L', $value);

        // // 用户组
        // $group = new \app\admin\model\AuthGroup;
        // $group = $group
        //     ->where('status', 1)
        //     ->order(['id' => 'asc'])
        //     ->select();
        // $value = "";
        // foreach ($group as $key => $val) {
        //     if ($key == 0)
        //     {
        //         $value = $val->title . '|' . $val->id;
        //     } else {
        //         $value = $value . ',' . $val->title . '|' . $val->id;
        //     }
        // }
        // $this->youxiaoxingAll($stuinfo, 'M', $value);

        // 设置格式
        // 给单元格加边框
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, //垂直居中
            ],
        ];
        $stuinfo->getStyle('A5:N154')->applyFromArray($styleArray);
        // 给单元格加边框
        $styleArray = [
            'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('argb' => '66cc99')
                ],
        ];
        $stuinfo->getStyle('A4:N4')->applyFromArray($styleArray);

        //告诉浏览器输出07Excel文件
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //告诉浏览器输出浏览器名称
        header('Content-Disposition: attachment;filename="教师信息批量录入表.xlsx"');
        //禁止缓存
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        $writer->save('php://output');
        ob_flush();
        flush();
        exit();

    }


    // 表格有效性
    private function youxiaoxing($obj, $cell, $value)
    {
        $yxx = $obj->getCell($cell)
            ->getDataValidation();
        $yxx->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
        $yxx->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
        $yxx->setAllowBlank(false);
        $yxx->setShowInputMessage(true);
        $yxx->setShowErrorMessage(true);
        $yxx->setShowDropDown(true);
        $yxx->setErrorTitle('输入错误');
        $yxx->setError('数值不在列表中');
        $yxx->setPromptTitle('从列表中选择');
        $yxx->setPrompt('请从下拉列表中选择一个值');
        $yxx->setFormula1('"' . $value . '"');
        return true;
    }


    private function youxiaoxingAll($obj, $colmun, $value)
    {

        $i = 5;
        while ($i <= 154)
        {

            $this->youxiaoxing($obj, $colmun . $i, $value);
            $i ++;
        }
        return true;
    }


    // 根据教师姓名、首拼、全拼搜索教师信息
    public function srcAdmin()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'searchval' => ''
                ,'school_id' => ''
                ,'teacher_id' => ''
                ,'page' => 1
                ,'limit' => 10
                ,'field' => 'id'
                ,'order' => 'desc'
            ], 'POST');

        $ad = new AD();
        $list = $ad->strSrcTeachers($src)
            ->visible([
                'id'
                ,'xingming'
                ,'adSchool' => ['jiancheng']
                ,'shengri'
            ]);
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = [
                'xingming' => $value->adSchool->jiancheng . ' -- ' .$value->xingming . ' -- ' . date('Y/m',$value->getData('shengri'))
                ,'id' => $value->id
            ];
        }
        $src['all'] = true;
        $cnt = $ad->strSrcTeachers($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 查询用户名是否重复
    public function srcUsername()
    {
        // 获取参数
        $srcfrom = $this->request
            ->only([
                'searchval' => ''
                ,'id' => ''
            ], 'POST');

        $ad = new AD();
        $data = $ad->onlyUsername($srcfrom);

        return json($data);
    }


    // 查询用户名是否重复
    public function srcPhone()
    {
        // 获取参数
        $srcfrom = $this->request
            ->only([
                'searchval' => ''
                ,'id'
            ], 'POST');

        $ad = new AD();
        $data = $ad->onlePhone($srcfrom);

        return json($data);
    }
}
