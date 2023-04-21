<?php

namespace app\admin\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用权限数据模型类
use app\admin\model\AuthRule as AR;

class AuthRule extends AdminBase
{
    // 显示权限列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '权限列表';
        $list['dataurl'] = '/admin/authrule/data';
        $list['teacher'] = '/admin/authrule/teacher';
        $list['student'] = '/admin/authrule/student';
        $list['status'] = '/admin/authrule/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取权限信息列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page'=>'1'
                ,'limit' => '10'
                ,'field' => 'id'
                ,'order' => 'asc'
                ,'searchval' => ''
            ], 'POST');

        // 根据条件查询数据
        $ar = new AR;
        $data = $ar->search($src);
        $src['all'] = true;
        $cnt = $ar->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建权限
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加权限'
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
            'title'
            ,'name'
            ,'pid'
            ,'condition'
            ,'paixu'
            ,'ismenu'
            ,'font'
            ,'beizhu'
            ,'url'
        ], 'POST');

        // 验证表单数据
        $validate = new \app\admin\validate\Rule;
        $result = $validate->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 保存数据
        $data = AR::create($list);
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改权限信息
    public function edit($id)
    {
        // 获取权限信息
        $list['data'] = AR::field('id, title, name, condition, pid, paixu, ismenu, font, url')
            ->find($id);

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑权限'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/admin/authrule/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新权限信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'name'
            ,'pid'
            ,'condition'
            ,'paixu'
            ,'ismenu'
            ,'font'
            ,'beizhu'
            ,'url'
        ], 'PUT');

        // 验证表单数据
        $validate = new \app\admin\validate\Rule;
        $result = $validate->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        $data = AR::where('id', $id)->update($list);
        $data >= 0 ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除权限
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = AR::destroy($id);
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置权限状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取权限信息
        $data = AR::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
