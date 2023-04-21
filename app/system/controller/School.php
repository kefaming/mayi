<?php

namespace app\system\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用单位数据模型类
use app\system\model\School as sch;

class School extends AdminBase
{
    // 单位列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '单位列表';
        $list['dataurl'] = '/system/school/data';
        $list['status'] = '/system/school/status';
        $list['kaoshi'] = '/system/school/kaoshi';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    //  获取单位列表数据
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'paixu'
                ,'order' => 'asc'
                ,'jibie_id' => array()
                ,'xingzhi_id' => array()
                ,'status' => '1'
                ,'searchval' => ''
            ],'POST');

        // 实例化
        $sch = new sch;
        $data = $sch->search($src);

        // 查询要显示的数据
        $data = $sch->search($src)
            ->visible([
                'id'
                ,'title'
                ,'jiancheng'
                ,'paixu'
                ,'status'
                ,'kaoshi'
                ,'dw_admin_count'
                ,'dwXingzhi'=>['title']
                ,'dwXueduan'=>['title']
                ,'dwJibie'=>['title']
                ,'update_time'
            ]);
        $src['all'] = true;
        $cnt = $sch->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 添加单位
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加单位'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
        );

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染
        return $this->view->fetch();
    }


    // 保存信息
    public function save()
    {

        // 获取表单数据
        $list = request()
            ->only([
                'title'
                ,'jiancheng'
                ,'biaoshi'
                ,'xingzhi_id'
                ,'jibie_id'
                ,'xueduan_id'
                ,'paixu' => 999
            ], 'post');

        // 验证表单数据
        $validate = new \app\system\validate\School;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();

        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 保存数据
        $data = sch::create($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 编辑单位
    public function edit($id)
    {

        // 获取单位信息
        $list['data'] = sch::field('id, title, jiancheng, biaoshi, xingzhi_id, jibie_id, status, xueduan_id, paixu, kaoshi')
            ->find($id);

        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'编辑单位'
            ,'butname'=>'修改'
            ,'formpost'=>'PUT'
            ,'url'=>'/system/school/update/'.$id,
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新单位信息
    public function update($id)
    {

        // 获取表单数据
        $list = request()
            ->only([
                'id'=>''
                ,'title'
                ,'jiancheng'
                ,'biaoshi'
                ,'xingzhi_id'
                ,'jibie_id'
                ,'xueduan_id'
                ,'kaoshi'
                ,'paixu' => 999
            ], 'put');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\system\validate\School;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        $data = sch::where('id', $id)->update($list);

        // 根据更新结果设置返回提示信息
        $data >= 0 ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除单位
    public function delete($id)
    {
        $id = request()->delete('id');
        $id = explode(',', $id);
        $data = sch::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置单位状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取单位信息
        $data = sch::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置单位状态
    public function setKaoshi()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取单位信息
        $data = sch::where('id', $id)->update(['kaoshi' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '考试状态成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 查询单位列表
    public function srcSchool()
    {
        // 获取表单数据
        $src = request()->only([
            'low' => '班级'
            ,'high' => '其它级'
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
        ], 'post');

        // 实例化单位模型
        $sch = new sch;
        $data = $sch->srcJibie($src)
            ->visible([
                'id'
                ,'title'
                ,'jiancheng'
            ]);
        $src['all'] = true;
        $cnt = $sch->srcJibie($src)->count();

        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 查询单位列表
    public function srcKaoshi()
    {
        // 获取表单数据
        $src = request()->only([
            'page' => '1'
            ,'limit' => '10'
            ,'field' => 'jibie_id'
            ,'order' => 'asc'
        ], 'post');
        // 实例化单位模型
        $sch = new sch;
        $data = $sch->kaoshi($src);
        $cnt = $data->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }
}
