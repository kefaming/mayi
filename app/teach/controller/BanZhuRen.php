<?php

namespace app\teach\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用班主任数据模型
use app\teach\model\BanZhuRen as bzr;

class BanZhuRen extends AdminBase
{
    // 主任列表
    public function index($banji_id)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '班主任列表';
        $list['dataurl'] = '/teach/banzhuren/data';
        $list['status'] = '/teach/banzhuren/status';
        $list['banji_id'] = $banji_id;

        $bj = new \app\teach\model\Banji;
        $bjInfo = $bj->where('id', $banji_id)
                ->append(['banjiTitle'])
                ->find();
        $list['bjTitle'] = $bjInfo->banjiTitle;

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取班主任列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'bfdate'
                ,'order' => 'desc'
                ,'banji_id' => ''
                ,'bfdate'
                ,'enddate'
                ,'searchval' => ''
            ], 'POST');

        // 查询数据
        $bzr = new bzr;
        $data = $bzr->search($src)
            ->visible([
                'id'
                ,'glAdmin' => ['xingming']
                ,'banji_id'
                ,'teacher_id'
                ,'bfdate'
                ,'update_time'
            ]);  # 查询数据
        $src['all'] = true;
        $cnt = $bzr->search($src)->count();
        $data = reset_data($data, $src);

        // 返回数据
        return json($data);
    }



    public function create($banji_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加班主任'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => '/teach/banzhuren/save'
        );

        $bj = new \app\teach\model\Banji;
        $bjInfo = $bj->where('id', $banji_id)->value('school_id');
        $list['data']['school_id'] = $bjInfo;
        $list['data']['banji_id'] = $banji_id;

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    /**
     * 保存班级
     *
     * @return \think\Response
     */
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'banji_id'
            ,'teacher_id'
            ,'bfdate'
        ], 'post');

        if(is_array($list['teacher_id']))
        {
            $list['teacher_id'] = $list['teacher_id'][0];
        }

        // 验证表单数据
        $validate = new \app\teach\validate\BanZhuRen;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result)
        {
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 保存数据
        $bj = new bzr();
        $data = $bj->save($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val'=>1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改学期信息
    public function edit($id)
    {
        // 获取学期信息
        $bzr = new bzr;
        $bjInfo = $bzr
            ->where('id', $id)
            ->field('id,teacher_id, banji_id, bfdate')
            ->with([
                'glAdmin' => function ($query) {
                    $query->field('id, xingming');
                },
                'glBanji' => function ($query) {
                    $query->field('id, school_id')
                        ->with([
                            'glSchool' => function($query){
                                $query->field('id, title, jiancheng');
                            },
                        ]);
                }
            ])
            ->find();
        $list['data']['school_id'] = $bjInfo->glBanji->school_id;
        $list['data']['banji_id'] = $bjInfo->banji_id;
        $list['data']['teacher_id'] = $bjInfo->teacher_id;
        $list['data']['xingming'] = $bjInfo->glAdmin->xingming;
        $list['data']['bfdate'] = $bjInfo->bfdate;
        $list['data']['selectname'] = $bjInfo->glBanji->glSchool->jiancheng . ' -- ' . $bjInfo->glAdmin->xingming;

       // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑班主任'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/teach/banzhuren/update/' . $id
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
            'bfdate'
            ,'teacher_id'
        ], 'put');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\teach\validate\BanZhuRen;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $bzrlist = bzr::find($id);
        $bzrlist->teacher_id = $list['teacher_id'];
        $bzrlist->bfdate = $list['bfdate'];
        $data = $bzrlist->save();

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

        $data = bzr::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
