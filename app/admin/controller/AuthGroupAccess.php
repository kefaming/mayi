<?php

namespace app\admin\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用用户权限关联数据模型
use app\admin\model\AuthGroupAccess as AGA;


class AuthGroupAccess extends AdminBase
{
	
    // 用户列表
    public function index($group_id)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '角色用户列表';
        $list['dataurl'] = '/admin/authgroupaccess/data';
        $list['group_id'] = $group_id;

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
                ,'school_id' => ''
                ,'group_id' => 0
            ], 'POST');

        // 根据数据查询信息
        $aga = new AGA;
        $data = $aga->search($src);
        $src['all'] = true;
        $cnt = $aga->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建
    public function create($group_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '分配用户'
            ,'butname' => '分配'
            ,'formpost' => 'POST'
            ,'group_id' => $group_id
            ,'url' => '/admin/authgroupaccess/save'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 保存
    public function saveAll()
    {
        // 获取表单数据
        $list = request()->only([
            'group_id'
        ], 'POST');
        $data = array();

        // 验证表单数据
        $validate = new \app\admin\validate\AuthGroupAccess;
        $aga = new AGA;

        // 查询所有用户
        $ad = new \app\admin\model\Admin;
        $adList = $ad
            ->where('id', '>', 2)
            ->where('status', 1)
            ->column(['id']);
        foreach ($adList as $key => $value) {
            // 组合数组
            $temp = [
                'group_id' => $list['group_id']
                ,'uid' => $value
            ];
            // 验证数据
            $result = $validate->check($temp);
            $msg = $validate->getError();
            if(!$result){
                continue;
            }
            // 查询数据是否存在
            $tempObj = $aga
                ->where('uid', $value)
                ->where('group_id', $list['group_id'])
                ->find();
            if($tempObj)
            {
                continue;
            }
            $data[] = $temp;
        }


        // 保存数据
        $data = $aga->saveAll($data);
        $cnt = $data->count();

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功' . $cnt . '条记录。', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 保存
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'uid'
            ,'group_id'
        ], 'POST');
        $list['uid'] = str_to_array($list['uid']);
        $data = array();

        // 验证表单数据
        $validate = new \app\admin\validate\AuthGroupAccess;
        $aga = new AGA;
        foreach ($list['uid'] as $key => $value) {
            // 组合数组
            $temp = [
                'group_id' => $list['group_id']
                ,'uid' => $value
            ];
            // 验证数据
            $result = $validate->check($temp);
            $msg = $validate->getError();
            if(!$result){
                continue;
            }
            // 查询数据是否存在
            $tempObj = $aga
                ->where('uid', $value)
                ->where('group_id', $list['group_id'])
                ->find();
            if($tempObj)
            {
                continue;
            }
            $data[] = $temp;
        }

        // 保存数据
        $data = $aga->saveAll($data);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = AGA::destroy($id, true);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
