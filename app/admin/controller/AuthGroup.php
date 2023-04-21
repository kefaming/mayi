<?php

namespace app\admin\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用角色数据模型类
use app\admin\model\AuthGroup as AG;


class AuthGroup extends AdminBase
{
    // 角色列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '角色列表';
        $list['dataurl'] = '/admin/authgroup/data';
        $list['status'] = '/admin/authgroup/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取角色列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'id'
                ,'order' => 'asc'
                ,'searchval' => ''
            ], 'POST');

        // 根据数据查询信息
        $ag = new AG;
        $data = $ag->search($src);
        $src['all'] = true;
        $cnt = $ag->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建角色
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加角色'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
        );

        $rule = new \app\admin\model\AuthRule;
        $src = [
            'status' => 1
            ,'all' => true
        ];
        $ruleList = $rule->search($src);
        $ruleSelect = array();
        $auth = $rule->digui($ruleList, $ruleSelect); # 递归获取所有权限
        $list['auth'] = $auth;

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染
        return $this->view->fetch();
    }


    // 保存角色信息
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'rules'
            ,'miaoshu'
        ], 'POST');
        $list['rules'] = implode(",", $list['rules']);

        // 验证表单数据
        $validate = new \app\admin\validate\RuleGroup;
        $result = $validate->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 保存数据
        $data = AG::create($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改角色信息
    public function edit($id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑角色'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/admin/authgroup/update/' . $id
        );

        $rule = new \app\admin\model\AuthRule;
        $src = [
            'status' => 1
            ,'all' => true
        ];
        $ruleList = $rule->search($src);
        $aglist = AG::where('id', $id)
            ->field('id, title, miaoshu, rules')
            ->find();
        $ruleSelect = explode(",", $aglist->rules);

        $auth = $rule->digui($ruleList, $ruleSelect, 0);
        $list['auth'] = $auth;
        $list['data'] =[
            'title' => $aglist->title
            ,'miaoshu' => $aglist->miaoshu
            ,'id' => $aglist->id
        ];

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新角色信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'rules'
            ,'miaoshu'
        ], 'PUT');
        $list['id'] = $id;
        $list['rules'] = implode(",", $list['rules']);

        // 验证表单数据
        $validate = new \app\admin\validate\RuleGroup;
        $result = $validate->check($list);
        $msg = $validate->getError();

        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        $data = AG::where('id', $id)->update($list);
        // 根据更新结果设置返回提示信息
        $data >= 0 ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除角色
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = AG::destroy($id);
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置角色状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 更新数据
        $data= AG::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
