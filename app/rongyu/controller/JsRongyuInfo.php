<?php

namespace app\rongyu\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用教师数据模型类
use app\rongyu\model\JsRongyuInfo as ryinfo;

// 引用PhpSpreadsheet类
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 引用上传文件
use app\tools\controller\File;


class JsRongyuInfo extends AdminBase
{
    /**
     * 显示教师荣誉册列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '荣誉列表';
        $list['dataurl'] = '/rongyu/jsryinfo/data';
        $list['status'] = '/rongyu/jsryinfo/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    /**
     *荣誉册中荣誉信息列表
     *
     * @return \think\Response
     */
    public function rongyuList($rongyuce_id)
    {
        // 荣誉数据模型
        $ry = new \app\rongyu\model\JsRongyu;
        // 设置要给模板赋值的信息
        $list['webtitle'] = $ry->where('id', $rongyuce_id)
            ->value('title') . ' 荣誉';
        $list['rongyuce_id'] = $rongyuce_id;
        $list['dataurl'] = '/rongyu/jsryinfo/data';
        $list['status'] = '/rongyu/jsryinfo/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    /**
     * 显示教师荣誉册列表
     *
     * @return \think\Response
     */
    public function ajaxData()
    {

        // 获取参数
        $src = $this->request
            ->only([
                'page'=>'1'
                ,'limit'=>'10'
                ,'field'=>'update_time'
                ,'order'=>'desc'
                ,'fzschool_id'=>array()
                ,'hjschool_id'=>array()
                ,'category_id'=>array()
                ,'rongyuce_id'=>''
                ,'subject_id'=>array()
                ,'searchval'=>''
            ], 'POST');

        // 实例化
        $ryinfo = new ryinfo;
        $data = $ryinfo->search($src);
        $src['all'] = true;
        $cnt = $ryinfo->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($rongyuce_id = 0)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'添加荣誉'
            ,'butname'=>'添加'
            ,'formpost'=>'POST'
            ,'url'=>'/rongyu/jsryinfo/save'
            ,'rongyuce'=>$rongyuce_id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    /**留备用
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'rongyuce_id'
            ,'title'
            ,'bianhao'
            ,'category_id'
            ,'hjschool_id'
            ,'subject_id'
            ,'hjshijian'
            ,'jiangxiang_id'
            ,'hjteachers'
            ,'cyteachers'
            ,'pic'
        ], 'put');

        // 判断获奖时间是否为空，如果为空，则使用荣誉册时间
        if($list['hjshijian'] == "")
        {
            $ryce = new \app\rongyu\model\JsRongyu;
            $ryceinfo = $ryce::where('id', $list['rongyuce_id'])
                    ->field('id, fzshijian')
                    ->find();
            $list['hjshijian'] = $ryceinfo->fzshijian;
        }

        // 实例化验证类
        $validate = new \app\rongyu\validate\JsRongyuInfo;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $data = ryinfo::create($list);

        // 声明教师数组
        $teacherlist = [];
        // 循环组成获奖教师信息
        $list['hjteachers'] = explode(',', $list['hjteachers']);
        foreach ($list['hjteachers'] as $key => $value) {
            $canyulist[] = [
                'teacher_id' => $value
                ,'category_id' => 11901
            ];
        }
        // 循环组成参与教师信息
        $list['cyteachers'] = explode(',', $list['cyteachers']);
        foreach ($list['cyteachers'] as $key => $value) {
            if($value !="") {
                $canyulist[] = [
                    'teacher_id' => $value
                    ,'category_id' => 11902
                ];
            }
        }

        // 添加新的获奖人与参与人信息
        $data->allJsry()->saveAll($canyulist);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1 ]
            : $data = ['msg'=> '数据处理错误', 'val' => 0 ];

        // 返回信息
        return json($data);
    }


    /**
     * 批量上传教师荣誉册图片
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function createAll($rongyuce_id)
    {

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '批量上传教师荣誉册图片'
            ,'butname' => '批传'
            ,'formpost' => 'POST'
            ,'url' => '/rongyu/jsryinfo/saveall/' . $rongyuce_id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 保存批传
    public function saveall($rongyuce_id)
    {
        // 获取表单数据
        $list = request()->only([
            'text'
            ,'serurl'
        ], 'post');

        // 获取表单上传文件
        $file = request()->file('file');
        // 上传文件并返回结果
        $data = \app\facade\File::saveFileInfo($file, $list, false);

        if($data['val'] != 1)
        {
            $data = ['msg' => '添加失败', 'val' => 0];
        }

        $data = ryinfo::create([
            'pic' => $data['url']
            ,'title' => '批传教师荣誉'
            ,'rongyuce_id' => $rongyuce_id
        ]);

        $data ? $data = ['msg' => '批传成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

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
        // 获取荣誉信息
        $list['data'] = ryinfo::where('id', $id)
                ->field('id, rongyuce_id, title, bianhao, hjschool_id, subject_id, jiangxiang_id, hjshijian, pic')
                ->with([
                    'hjJsry' => function($query){
                        $query->field('rongyu_id, teacher_id')
                            ->with([
                                'teacher' => function($query){
                                    $query->field('id, xingming');
                                }]
                            );
                    },
                    'cyJsry' => function($query){
                        $query->field('rongyu_id, teacher_id')
                            ->with([
                                'teacher' => function($query){
                                    $query->field('id, xingming');
                                }]
                            );
                    },
                    'ryTuce' => function($query){
                        $query->field('id, fzshijian');
                    }
                ])
                ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑荣誉'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/rongyu/jsryinfo/update/' . $id
            ,'rongyuce_id' => $id
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
            'bianhao'
            ,'title'
            ,'category_id'
            ,'hjschool_id'
            ,'subject_id'
            ,'hjshijian'
            ,'jiangxiang_id'
            ,'hjteachers'
            ,'cyteachers'
            ,'pic'
        ], 'put');
        $list['id'] = $id;
        // 判断获奖时间是否为空，如果为空，则使用荣誉册时间
        if($list['hjshijian'] == "")
        {
            $ryinfo = ryinfo::where('id', $id)
                    ->field('id, rongyuce_id')
                    ->with([
                        'ryTuce'=>function($query){
                            $query->field('id, fzshijian');
                        }
                    ])
                    ->find();
            $list['hjshijian'] = $ryinfo->ry_tuce->fzshijian;
        }

        // 实例化验证类
        $validate = new \app\rongyu\validate\JsRongyuInfo;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();

        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $data = ryinfo::update($list);

        // 删除原来的获奖人与参与人信息
        $data->allJsry->delete(true);
        // 声明教师数组
            $teacherlist = [];
            // 循环组成获奖教师信息
            $list['hjteachers'] = explode(',', $list['hjteachers']);
            foreach ($list['hjteachers'] as $key => $value) {
                $canyulist[] = [
                    'teacher_id' => $value
                    ,'category_id' => 11901
                ];
            }
                // 循环组成参与教师信息
            $list['cyteachers'] = explode(',', $list['cyteachers']);
            foreach ($list['cyteachers'] as $key => $value) {
                if ($value!="") {
                    $canyulist[] = [
                        'teacher_id' => $value
                        ,'category_id' => 11902
                    ];
                }
            }

        // 添加新的获奖人与参与人信息
        $data->allJsry()->saveAll($canyulist);

        // 根据更新结果设置返回提示信息
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

        $data = ryinfo::destroy($id);

        // 根据更新结果设置返回提示信息
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
        $data = ryinfo::where('id',$id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }



    // 下载荣誉信息
    public function outXlsx($rongyuce_id)
    {
        $ryinfo = new ryinfo();
        $list = $ryinfo->srcTuceRy($rongyuce_id);

        if($list->isEmpty())
        {
            $this->error('没有找到要下载的信息呀~');
            return '';
        }else{
            $filename = $list[0]['ryTuce']['title'];
            $fzschool = $list[0]['ryTuce']['fz_school']['title'];
            $fzshijian = strtotime($list[0]['ryTuce']['fzshijian']);
            $fzshijian = date('Ym', $fzshijian);
        }

        //通过工厂模式创建内容
        ob_start();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("uploads/rongyu_teacher/js_rongyu.xlsx");
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('A1')->setValue($filename);
        $worksheet->getCell('A2')->setValue('发证单位:' . $fzschool);
        $worksheet->getCell('G2')->setValue('发证时间:' . $list[0]['ryTuce']['fzshijian']);
        // 循环为excel每行赋值
        foreach ($list as $key => $value) {
            $myrowid = $key + 4;
            $worksheet->getCell('A' . $myrowid)->setValue($key + 1);
            $worksheet->getCell('B' . $myrowid)->setValue($value->title);
            $names = '';
            foreach ($value->hjJsry as $k => $val) {
                if (isset($val->teacher)) {
                    if($k == 0)
                    {
                        $names = $val->teacher->xingming;
                    }else{
                        $names = $names . '、' .$val->teacher->xingming;
                    }
                }
            }
            $worksheet->getCell('C' . $myrowid)->setValue($names);
            if($value->hj_school){
                $worksheet->getCell('D' . $myrowid)->setValue($value->hj_school->jiancheng);
            }
            if($value->ry_subject){
                $worksheet->getCell('E' . $myrowid)->setValue($value->ry_subject->title);
            }
            $names = '';
            foreach ($value->cyJsry as $k => $val) {
                if($k == 0)
                {
                    $names = $val->teacher->xingming;
                }else{
                    $names = $names . '、' .$val->teacher->xingming;
                }
            }
            $worksheet->getCell('F' . $myrowid)->setValue($names);
            if($value->jx_category){
                $worksheet->getCell('G' . $myrowid)->setValue($value->jx_category->title);
            }
            $worksheet->getCell('H' . $myrowid)->setValue($value->bianhao);
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
            $worksheet->getStyle('A10:I' . ($key + 4))->applyFromArray($styleArray);
            // 设置行高
            for($i = 10;  $i <= ($key + 4); $i ++){
                $worksheet->getRowDimension($i)->setRowHeight(30);
            }
        }

        $worksheet->getStyle('A4')->applyFromArray($styleArray);

        //告诉浏览器输出07Excel文件
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //告诉浏览器输出浏览器名称
        header('Content-Disposition: attachment;filename="' . $filename . $fzshijian . '.xlsx"');
        //禁止缓存
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        $writer->save('php://output');
        ob_flush();
        flush();
        exit();
    }


    // 查询荣誉参与人信息
    public function srcCy()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'str' => ''
                ,'rongyu_id' => ''
                ,'category_id' => ''
                ,'field' => 'id'
                ,'order' => 'asc'
            ], 'POST');

        $cy = new \app\rongyu\model\JsRongyuCanyu;
        $list = $cy->searchCanyu($src);
        $data = array();
        foreach ($list as $key => $value) {
            if($value->teacher)
            {
                $data[] = [
                    'xingming' => $value->teacher->adSchool->jiancheng . '--' .$value->teacher->xingming
                    ,'id' => $value->teacher_id
                    ,'selected' => true
                    ,'serid' => $value->id
                ];
            }
        }
        $src['all'] = true;
        $cnt = $cy->searchCanyu($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }
}
