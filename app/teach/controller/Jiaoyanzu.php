<?php

namespace app\teach\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用班级数据模型类
use app\teach\model\Jiaoyanzu as jyzmod;

class Jiaoyanzu extends AdminBase
{
    // 显示学期列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '教研组列表';
        $list['dataurl'] = 'jiaoyanzu/data';
        $list['status'] = '/teach/jiaoyanzu/status';

        // 模板赋值
        $this->view->assign('list',$list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取学期信息列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'id'
                ,'order' => 'desc'
                ,'category_id' => ''
                ,'school_id' => ''
                ,'searchval' => ''
            ], 'POST');

        if (session('user_id') != 1 && session('user_id') != 2) {
            $adInfo = \app\facade\OnLine::myInfo();
            $src['school_id'] = $adInfo->school_id;
        }

        // 根据条件查询数据
        $jyz = new jyzmod;
        $data = $jyz->search($src)
            ->visible([
                'id'
                ,'title'
                ,'ruxuenian'
                ,'glCategory' => ['title']
                ,'glSchool' => ['title']
                ,'subject_id'
                ,'status'
                ,'update_time'
            ]);
        $src['all'] = true;
        $cnt = $jyz->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }



    // 创建学期
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'添加教研组'
            ,'butname'=>'添加'
            ,'formpost'=>'POST'
            ,'url'=>'save'
        );
        $list['data']['subject'] = array();

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
            ,'ruxuenian'
            ,'category_id'
            ,'school_id'
            ,'subject_id'
            ,'beizhu'
        ], 'post');

        if ($list['category_id'] == 12501) {
            unset($list['title'], $list['subject_id']);
        } else{
            unset($list['ruxuenian']);
        }

        // 验证表单数据
        $validate = new \app\teach\validate\Jiaoyanzu;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 保存数据
        $data = jyzmod::create($list);
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        // 返回信息

        return json($data);
    }


    // 修改学期信息
    public function edit($id)
    {
        // 获取学期信息
        $list['data'] = jyzmod::field('id, title, ruxuenian, category_id, school_id, subject_id, beizhu')
            ->find($id);
        $subject = $list['data']->getData('subject_id');
        $list['data']['subject'] = explode('|', $subject);

       // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑教研组'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/teach/jiaoyanzu/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list',$list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新学期信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'ruxuenian'
            ,'category_id'
            ,'subject_id'
            ,'school_id'
            ,'beizhu'
        ], 'put');
        $list['id'] = $id;

        if ($list['category_id'] == 12501) {
            unset($list['title'], $list['subject_id']);
        } else{
            unset($list['ruxuenian']);
        }

        // 验证表单数据
        $validate = new \app\teach\validate\Jiaoyanzu;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $jiaoyanzhulist = jyzmod::find($id);
        if ($list['category_id'] == 12501) {
            $jiaoyanzhulist->ruxuenian = $list['ruxuenian'];
        } else{
            $jiaoyanzhulist->title = $list['title'];
            $jiaoyanzhulist->subject_id = $list['subject_id'];
        }
        $jiaoyanzhulist->category_id = $list['category_id'];
        $jiaoyanzhulist->school_id = $list['school_id'];
        $jiaoyanzhulist->beizhu = $list['beizhu'];
        $data = $jiaoyanzhulist->save();

        // 根据更新结果设置返回提示信息
        $data>=0 ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除学期
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = jyzmod::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data =['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置学期状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取学期信息
        $data = jyzmod::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }

}
