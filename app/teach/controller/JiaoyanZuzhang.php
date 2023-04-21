<?php

namespace app\teach\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用班级数据模型类
use app\teach\model\JiaoyanZuzhang as jyzzmod;

class JiaoyanZuzhang extends AdminBase
{
    // 主任列表
    public function index($jiaoyanzu_id)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '考研组长列表';
        $list['dataurl'] = '/teach/zuzhang/data';
        $list['status'] = '/teach/zuzhang/status';
        $list['jiaoyanzu_id'] = $jiaoyanzu_id;

        $jyz = new \app\teach\model\Jiaoyanzu;
        $jyzInfo = $jyz->where('id', $jiaoyanzu_id)
                ->find();
        $list['title'] = $jyzInfo->title;

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取教研组长列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'bfdate'
                ,'order' => 'desc'
                ,'jiaoyanzu_id' => ''
                ,'bfdate'
                ,'searchval' => ''
            ], 'POST');

        // 查询数据
        $jyzzmod = new jyzzmod;
        $data = $jyzzmod->search($src)
            ->visible([
                'id'
                ,'glTeacher' => ['xingming']
                ,'jiaoyanzu_id'
                ,'teacher_id'
                ,'bfdate'
                ,'update_time'
            ]);  # 查询数据

        $src['all'] = true;
        $cnt = $jyzzmod->search($src)->count();
        $data = reset_data($data, $cnt);

        // 返回数据
        return json($data);
    }



    public function create($jiaoyanzu_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加教研组长'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => '/teach/zuzhang/save'
        );

        $jyz = new \app\teach\model\Jiaoyanzu;
        $jyzInfo = $jyz->where('id', $jiaoyanzu_id)->value('school_id');
        $list['data']['school_id'] = $jyzInfo;
        $list['data']['jiaoyanzu_id'] = $jiaoyanzu_id;

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    /**
     * 保存教研组长
     *
     * @return \think\Response
     */
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'jiaoyanzu_id'
            ,'teacher_id'
            ,'bfdate'
        ], 'post');

        if(is_array($list['teacher_id']))
        {
            $list['teacher_id'] = $list['teacher_id'][0];
        }

        // 验证表单数据
        $validate = new \app\teach\validate\JiaoyanZuzhang;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result)
        {
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 保存数据
        $bj = new jyzzmod();
        $data = $bj->save($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val'=>1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改教研组长信息
    public function edit($id)
    {
        // 获取学期信息
        $jyzzmod = new jyzzmod;
        $bjInfo = $jyzzmod
            ->where('id', $id)
            ->field('id,teacher_id, jiaoyanzu_id, bfdate')
            ->with([
                'glTeacher' => function ($query) {
                    $query->field('id, xingming');
                },
                'glJiaoyanzu' => function ($query) {
                    $query->field('id, school_id')
                        ->with([
                            'glSchool' => function($query){
                                $query->field('id, title, jiancheng');
                            },
                        ]);
                }
            ])
            ->find();

        $list['data']['jiaoyanzu_id'] = $bjInfo->jiaoyanzu_id;
        $list['data']['teacher_id'] = $bjInfo->teacher_id;
        $list['data']['bfdate'] = $bjInfo->bfdate;
        $list['data']['selectname'] = $bjInfo->glJiaoyanzu->glSchool->jiancheng . ' -- ' . $bjInfo->glTeacher->xingming;

       // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑教研组长'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/teach/zuzhang/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list',$list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新教研组长信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'bfdate'
            ,'teacher_id'
        ], 'put');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\teach\validate\JiaoyanZuzhang;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $jyzzmodlist = jyzzmod::find($id);
        $jyzzmodlist->teacher_id = $list['teacher_id'];
        $jyzzmodlist->bfdate = $list['bfdate'];
        $data = $jyzzmodlist->save();

        // 根据更新结果设置返回提示信息
        $data>=0 ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
     * 删除班级
     *
     * @return \think\Response
     */
    public function delete()
    {

        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = jyzzmod::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
