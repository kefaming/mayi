<?php

namespace app\keti\controller;

// 引用控制器基类
use app\base\controller\AdminBase;

// 引用课题数据模型
use app\keti\model\Lixiang as lx;
// 引用PhpSpreadsheet类
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Lixiang extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '课题册列表';
        $list['dataurl'] = '/keti/lixiang/data';
        $list['status'] = '/keti/lixiang/data';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    /**
     * 显示课题册列表
     *
     * @return \think\Response
     */
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'lxshijian'
                ,'order' => 'desc'
                ,'lxdanwei_id' => array()
                ,'category_id' => array()
                ,'searchval' => ''
            ], 'POST');

        // 根据条件查询数据
        $keti = new lx;
        $data = $keti->search($src);
        $src['all'] = true;
        $cnt = $keti->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function list($lixiang_id)
    {

        $kt = new lx;
        // 设置要给模板赋值的信息
        $list['webtitle'] = $kt->where('id', $lixiang_id)->value('title') . ' 列表';
        $list['lixiang_id'] = $lixiang_id;
        $list['dataurl'] = '/keti/info/data';
        $list['status'] = '/keti/info/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加课题册'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'category_id'
            ,'lxshijian'
            ,'lxdanwei_id'
        ], 'post');

        // 实例化验证模型
        $validate = new \app\keti\validate\Keti;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 保存数据
        $data = lx::create($list);
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        // 获取荣誉册信息
        $list['data'] = lx::where('id', $id)
                ->field('id, title, category_id, lxshijian, lxdanwei_id')
                ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑课题册'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/keti/lixiang/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'category_id'
            ,'lxshijian'
            ,'lxdanwei_id'
        ], 'put');
        $list['id'] = $id;

        // 实例化验证类
        $validate = new \app\keti\validate\Keti;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 更新数据
        $data = lx::update($list);
        $data ? $data = ['msg' => '更新成功', 'val' => 1]
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

        $data = lx::destroy($id);
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


        // 设置荣誉状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取学生信息
        $data = lx::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 下载课题信息表
    public function outXlsx($lixiang_id)
    {
        $ketiinfo = new \app\keti\model\KetiInfo;
        $list = $ketiinfo->srcLixiangCe($lixiang_id);

        if($list->isEmpty())
        {
            $this->error('兄弟，没有要下载的信息呀~', '/login/err');
        }else{
           $keticename = $list[0]['glLixiang']['title'];
           $lxdanwei = $list[0]['glLixiang']['ktLxdanwei']['title'];
           $lxshijian = strtotime($list[0]['lxshijian']);
           $lxshijian = date('Ym', $lxshijian);
        }

        //通过工厂模式创建内容
        ob_start();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('uploads/keti/lixiang.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('A1')->setValue($keticename . '立项汇总');
        $worksheet->getCell('A2')->setValue('发证单位:' . $lxdanwei);
        $worksheet->getCell('G2')->setValue('发证时间:' . $list[0]['glLixiang']['lxshijian']);
        // 循环为excel每行赋值
        foreach ($list as $key => $value) {
            $myrowid = $key + 4;
            $worksheet->getCell('A' . $myrowid)->setValue($key + 1);
            $worksheet->getCell('B' . $myrowid)->setValue($value->title);
            $worksheet->getCell('C' . $myrowid)->setValue($value->bianhao);
            // 课题主持人
            if($value->ktZcr){
                $str = '';
                foreach ($value->ktZcr as $k => $val) {
                    if($k==0)
                    {
                        $str = $val->teacher->xingming;
                    }else{
                        $str = $str . '、' . $val->teacher->xingming;
                    }
                }
                $worksheet->getCell('D' . $myrowid)->setValue($str);
            }
            // 课题负责单位
            if($value->fzSchool){
                $worksheet->getCell('E' . $myrowid)->setValue($value->fzSchool->jiancheng);
            }
            // 课题参与人
            if($value->ktCy){
                $str = '';
                foreach ($value->ktCy as $k => $val) {
                    if($k==0)
                    {
                        $str = $val->teacher->xingming;
                    }else{
                        $str = $str . '、' . $val->teacher->xingming;
                    }
                }
                $worksheet->getCell('F' . $myrowid)->setValue($str);
            }
            $worksheet->getCell('G' . $myrowid)->setValue($value->jddengji);
        }

        // 给单元格加边框
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        if($key + 4 > 9)
        {

            $worksheet->getStyle('A10:H' . ($key + 4))->applyFromArray($styleArray);
            // 设置行高
            for($i = 10;  $i <= ($key + 4); $i ++){
                $worksheet->getRowDimension($i)->setRowHeight(30);
            }

        }

        $worksheet->getStyle('A4')->applyFromArray($styleArray);
        $xlsxTitle = $keticename . $lxshijian . '立项汇总.xlsx';
        //告诉浏览器输出07Excel文件
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //告诉浏览器输出浏览器名称
        header('Content-Disposition: attachment;filename="' . $xlsxTitle . '"');
        //禁止缓存
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        $writer->save('php://output');
        ob_flush();
        flush();
        exit();
    }
}
