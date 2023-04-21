<?php

namespace app\keti\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用课题信息数据模型类
use app\keti\model\KetiInfo as ktinfo;

// 引用上传文件
use app\tools\controller\File;


class KetiInfo extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '课题列表';
        $list['dataurl'] = '/keti/info/data';
        $list['status'] = '/keti/info/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }

    /**
     * 显示课题信息列表
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
                    ,'field' => 'update_time'
                    ,'order' => 'desc'
                    ,'lxdanwei_id' => array()
                    ,'lxcategory_id' => array()
                    ,'fzdanwei_id' => array()
                    ,'subject_id' => array()
                    ,'category_id' => array()
                    ,'jddengji_id' => array()
                    ,'lixiang_id' => ''
                    ,'jieti_id' => ''
                    ,'searchval' => ''
                ],'POST');

        // 实例化
        $ktinfo = new ktinfo;
        $data = $ktinfo->search($src);
        $src['all'] = true;
        $cnt = $ktinfo->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($lixiang_id=0)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'添加课题册',
            'butname'=>'添加',
            'formpost'=>'POST',
            'url'=>'/keti/info/save',
            'lixiang_id'=>$lixiang_id
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
            'lixiang_id'
            ,'title'
            ,'bianhao'
            ,'fzdanwei_id'
            ,'subject_id'
            ,'category_id'
            ,'jhjtshijian'
            ,'teacher_id'
            ,'lxpic'
        ], 'POST');

        // 实例化验证类
        $validate = new \app\keti\validate\KetiInfo;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $data = ktinfo::create($list);

        // 声明教师数组
            $teacherlist = [];
            $list['teacher_id'] = array_unique(explode(',', $list['teacher_id']));
            // 循环组成获奖教师信息
            foreach ($list['teacher_id'] as $key => $value) {
                if ($value != "") {
                    $canyulist[] = [
                        'teacher_id' => $value
                        ,'category_id' => 11901
                    ];
                }
            }

        // 添加新的获奖人与参与人信息
        $cy = $data->ktZcr()->saveAll($canyulist);

        // 根据更新结果设置返回提示信息
        if($cy){
            $data = ['msg' => '添加成功', 'val' => 1];
        }else{
            $data = ['msg' => '数据处理错误', 'val' => 0];
            $data->delete(true);
        }

        // 返回信息
        return json($data);
    }


    // 批量上传立项通知书
    public function createAll($lixiang_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'批量添加课题信息,'
            ,'butname'=>'批传'
            ,'formpost'=>'POST'
            ,'url'=>'/keti/info/saveall/' . $lixiang_id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 批量保存图片
    public function saveAll($lixiang_id)
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

        $data = ktinfo::create([
            'lxpic' => $data['url']
            ,'title' => '批传立项'
            ,'lixiang_id' => $lixiang_id
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
        // 获取课题信息
        $list['data'] = ktinfo::where('id',$id)
                ->field('id, title, fzdanwei_id, bianhao, subject_id, category_id, jhjtshijian, lxpic')
                ->with([
                    'ktZcr'=>function($query){
                        $query->field('ketiinfo_id,teacher_id')
                            ->with([
                                'teacher'=>function($q){
                                    $q->field('id, xingming');
                                }
                            ]);
                    },
                ])
                ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'编辑课题',
            'butname'=>'修改',
            'formpost'=>'PUT',
            'url'=>'/keti/info/update/' . $id,
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
            ,'bianhao'
            ,'fzdanwei_id'
            ,'subject_id'
            ,'category_id'
            ,'jhjtshijian'
            ,'teacher_id'
            ,'lxpic'
        ], 'PUT');
        $list['id'] = $id;

        // 验证数据
        $validate = new \app\keti\validate\KetiInfo;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $data = ktinfo::update($list); # 更新课题信息
        $data->ktZcr->delete(true);  # 删除原来课题主持人信息
        // 声明教师数组
            $teacherlist = [];
            // 循环组成获奖教师信息
            $list['teacher_id'] = explode(',', $list['teacher_id']);
            foreach ($list['teacher_id'] as $key => $value) {
                if ($value != "") {
                    $canyulist[] = [
                        'teacher_id' => $value
                        ,'category_id' => 11901
                    ];
                }
                    
            }
        $data = $data->ktZcr()->saveAll($canyulist); # 添加新课题主持人

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 获取立项课题信息
    public function read($id) {
        $info = ktinfo::where('id', $id)->field('lxpic, jtpic')->find();
        $list['webtitle'] = '帐号信息';
        $list['lxpic'] = $info->lxpic;
        $list['jtpic'] = $info->jtpic;
        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch('read');
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

        $data = ktinfo::destroy($id);
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
        $data = ktinfo::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 结题页面
    public function jieTi($id, $jieti_id="")
    {
        // 获取课题信息
        $list['data'] = ktinfo::where('id', $id)
                ->field('id, bianhao, title, jddengji_id, jtshijian, jtpic,beizhu')
                ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'编辑结题'
            ,'butname'=>'修改'
            ,'formpost'=>'PUT'
            ,'url'=>'/keti/info/jietiupdate/' . $id
            ,'jieti_id' => $jieti_id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 更新结题信息
    public function jtUpdate($id)
    {
        // 获取表单数据
        $list = request()->only([
            'jtpic'
            ,'jddengji_id'
            ,'jtshijian'
            ,'teacher_id'=>array()
            ,'canyu_id'=>array()
            ,'beizhu'
        ], 'PUT');
        $list['id'] = $id;

        // 实例化验证类
        $validate = new \app\keti\validate\KetiInfo;
        $result = $validate->scene('editjieti')->check($list);
        $msg = $validate->getError();
        if (!$result) {
            return json(['msg' => $msg, 'val' => 0]);
        }
        if ($list['jddengji_id'] == 11804 && $list['beizhu']=='') {
            return json(['msg' => '流失的课题必须在备注中写明原因', 'val' => 0]);
        }
        // 更新数据
        $data = ktinfo::update($list);

        // 删除原来的获奖人与参与人信息
        $data->ktCy->delete(true);
        $data->ktZcr->delete(true);
        // 声明教师数组
            $teacherlist = [];
            $canyulist = [];
            // 循环组成获奖教师信息
            $list['teacher_id'] = array_unique(explode(',', $list['teacher_id']));
            foreach ($list['teacher_id'] as $key => $value) {
                if ($value != "") {
                    $canyulist[] = [
                        'teacher_id' => $value
                        ,'category_id' => 11901
                    ];
                }
            }
            $list['canyu_id'] = array_unique(explode(',', $list['canyu_id']));
            foreach ($list['canyu_id'] as $key => $value) {
                if ($value != "") {
                    $canyulist[] = [
                        'teacher_id' => $value
                        ,'category_id' => 11902
                    ];
                }
            }

        // 添加新的获奖人与参与人信息
        if (count($canyulist)>0) {
            $data = $data->ktCy()->saveAll($canyulist);
        }else{
            $data = true;
        }


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
    public function deleteJieti()
    {

        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $temp = [];
        foreach ($id as $key => $value) {
            $temp[] = [
                'id' => $value
                ,'jtshijian' => null
                ,'jddengji_id' => 11801
                ,'jtpic' => null
                ,'jieti_id' => 0
            ];
        }

        $ktinfo = new ktinfo;
        $data = $ktinfo->saveAll($temp);
        foreach ($data as $key => $value) {
            $value->ktCy->delete(true);
            $value->ktZcr->delete(true);
        }

        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 查询课题参与人信息
    public function srcCy()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'str' => ''
                ,'ketiinfo_id' => ''
                ,'category_id' => ''
                ,'field' => 'id'
                ,'order' => 'desc'
                ,'teacher_id' => ''
            ], 'POST');

        $cy = new \app\keti\model\KetiCanyu;
        $list = $cy->searchCanyu($src);
        $data = array();
        foreach ($list as $key => $value) {
            if($value->teacher)
            {
                $data[] = [
                    'xingming' => $value->teacher->adSchool->jiancheng . '--' .$value->teacher->xingming
                    ,'id' => $value->teacher_id
                    ,'selected' => true
                ];
            }
        }

        $src['all'] = true;
        $cnt = $cy->searchCanyu($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 查询课题信息
    public function srcInfo()
    {
        // 获取参数
        $src = $this->request
            ->only([
                 'page' => '1'
                ,'limit' => '10'
                ,'field' => 'id'
                ,'order' => 'desc'
                ,'fzdanwei_id' => ''
                ,'searchval' => ''
                ,'jddengji_id' => 11801
            ], 'POST');

        $info = new ktinfo;
        $list = $info->search($src)
                ->visible([
                'id'
                ,'bianhao'
                ,'title'
            ]);
        $data = array();
        foreach ($list as $key => $value) {
            $zcr = "";
            foreach ($value->ktZcr as $key=>$v) {
                if($key == 0) {
                    $zcr = $v->teacher->xingming;
                }else {
                    $zcr = $zcr . '、' . $v->teacher->xingming;
                }
            }
            $data[] = [
                'id' => $value->id
                ,'srctitle' => '标题：'.$value->title . ' ‖ 编号：' . $value->bianhao  . ' ‖ 主持人：' . $zcr . ' ‖ 鉴定结果：' .  $value->ktJdDengji->title
                ,'title' => '标题：'.$value->title . ' ‖ 编号：' . $value->bianhao
            ];
        }
        $src['all'] = true;
        $cnt = count($data);
        $data = reset_data($data, $cnt);
        return json($data);
    }
}
