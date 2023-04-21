<?php

namespace app\rongyu\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用教师数据模型类
use app\rongyu\model\DwRongyu as DW;
// 引用上传文件
use app\tools\controller\File;


class Danwei extends AdminBase
{
    /**
     * 显示单位荣誉列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '单位荣誉列表';
        $list['dataurl'] = '/rongyu/danwei/data';
        $list['status'] = '/rongyu/danwei/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    /**
     * 显示单位荣誉列表
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
                ,'fzschool_id' => array()
                ,'hjschool_id' => array()
                ,'category_id' => array()
                ,'searchval' => ''
            ],'POST');

        // 查询数据
        $DW = new DW; 
        $data = $DW->search($src);
        $src['all'] = true;
        $cnt = $DW->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
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
            'webtitle' => '添加单位荣誉'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
        );

        // 模板赋值
        $this->view->assign('list',$list);
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
            'url'
            ,'project'
            ,'title'
            ,'teacher_id' => array()
            ,'hjschool_id'
            ,'category_id'
            ,'fzshijian'
            ,'fzschool_id'
            ,'jiangxiang_id'
        ], 'post');

        // 实例化验证模型
        $validate = new \app\rongyu\validate\DwRongyu;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg'=>$msg,'val'=>0]);
        }

        // 保存数据
        $data = DW::create($list);

        // 重组教师id
        $list['teacher_id'] = explode(',', $list['teacher_id']);
        foreach ($list['teacher_id'] as $key => $value) {
            if ($value!="") {
                $teachers[]['teacher_id'] = $value;
            }
        }

        $data->cyDwry()->saveAll($teachers);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
     * 批量上传单位荣誉图片
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    // 批量添加
    public function createAll()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '批量上传荣誉图片'
            ,'butname' => '批传'
            ,'formpost' => 'POST'
            ,'url' => '/rongyu/danwei/saveall'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }

    // 保存批传
    public function saveAll()
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

        if($data['val'] != true)
        {
            $data=['msg' => '添加失败', 'val' => 0];
            return json($data);
        }

        $createInfo = [
            'url' => $data['url']
            ,'title' => '批传单位荣誉图片'
            ,'project' => '无'
        ];

        // 实例化验证类
        $validate = new \app\rongyu\validate\DwRongyu;
        $result = $validate->scene('createall')->check($createInfo);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg'=>$msg,'val'=>0]);;
        }

        $data = DW::create($createInfo);

        $data ? $data = ['msg' => '批传成功，请关闭对话框并刷新列表。','val' => 1] :
            $data = ['msg' => '数据处理错误','val' => 0];

        return json($data);
    }


     /**
     * 上传荣誉图片并保存
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
     public function upload()
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
        // 获取学生信息
        $list['data'] = DW::where('id', $id)
                ->field('id, title, project, category_id, hjschool_id, fzshijian, fzschool_id, jiangxiang_id, url')
                ->with([
                    'cyDwry' => function($query){
                        $query->field('rongyu_id, teacher_id')
                        ->with([
                            'teacher' => function($query){
                                $query->field('id, xingming');
                            }
                        ]);
                    },
                ])
                ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'编辑单位荣誉'
            ,'butname'=>'修改'
            ,'formpost'=>'PUT'
            ,'url'=>'/rongyu/danwei/update/' . $id
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
            ,'project'
            ,'category_id'
            ,'hjschool_id'
            ,'fzshijian'
            ,'fzschool_id'
            ,'jiangxiang_id'
            ,'teacher_id' => array()
            ,'url'
        ], 'put');
        $list['id'] = $id;

        // 实例化验证类
        $validate = new \app\rongyu\validate\DwRongyu;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 更新数据
        $DW = new DW();
        $data = DW::update($list);

        // 删除原来的参与教师
        $data->cyDwry->delete(true);

        // 声明参与教师数组
        $canyulist = [];
        $list['teacher_id'] = explode(',', $list['teacher_id']);
        // 循环组成参与教师
        foreach ($list['teacher_id'] as $key => $value) {
            if ($value!="") {
                $canyulist[] = [
                    'teacher_id' => $value,
                ];
            }
        }
        //  更新参考教师
        $data->cyDwry()->saveAll($canyulist);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '更新成功', 'val' => 1] :
            $data = ['msg' => '数据处理错误', 'val' => 0];

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

        $data = DW::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1] :
            $data = ['msg' => '数据处理错误', 'val' => 0];

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
        $data = DW::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 查询单位参与人信息
    public function srcCy()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'str' => ''
                ,'rongyu_id' => ''
                ,'field' => 'id'
                ,'order' => 'desc'
                ,'teacher_id' => ''
            ], 'POST');

        $cy = new \app\rongyu\model\DwRongyuCanyu;
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
        $data = reset_data($data, $src);

        return json($data);
    }
}
