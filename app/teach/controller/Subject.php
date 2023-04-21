<?php

namespace app\teach\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用学科数据模型类
use app\teach\model\Subject as SJ;

class Subject extends AdminBase
{
    /**
     * 学科列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '学科列表';
        $list['dataurl'] = 'subject/data';
        $list['status'] = '/teach/subject/status';
        $list['kaoshi'] = '/teach/subject/kaoshi';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取学科信息列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'update_time'
                ,'order' => 'asc'
                ,'kaoshi' => ''
                ,'status' => ''
                ,'delete_time' => ''
                ,'xingzhi' => array()
                ,'searchval' => ''
            ],'POST');

        // 按条件查询数据
        $sj = new SJ;
        $data = $sj->search($src)
            ->visible([
                'id'
                ,'title'
                ,'jiancheng'
                ,'lieming'
                ,'sbjCategory' => ['title']
                ,'status'
                ,'kaoshi'
                ,'paixu'
                ,'update_time'
            ]);

        $src['all'] = true;
        $cnt = $sj->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建学科
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加学科'
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
            ,'jiancheng'
            ,'category_id'
            ,'kaoshi'
            ,'paixu'
            ,'lieming'
        ], 'post');

        // 验证表单数据
        $validate = new \app\teach\validate\Subject;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 保存数据
        $data = SJ::create($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改学科信息
    public function edit($id)
    {
        // 获取学科信息
        $list['data'] = SJ::field('id, title, jiancheng, category_id, kaoshi, lieming, paixu, status')
            ->find($id);

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑学科'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/teach/subject/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新学科信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'jiancheng'
            ,'category_id'
            ,'kaoshi'
            ,'lieming'
            ,'paixu'
        ], 'put');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\teach\validate\Subject;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $data = SJ::update($list, ['id'=>$id]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除学科
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        // 删除数据
        $data = SJ::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置学科状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取学科信息
        $data = SJ::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功' , 'val' => 1]
            : $data = ['msg' => '数据处理错误' , 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 恢复删除
    public function restoreDel()
    {
        //  获取id变量
        $id = request()->post('id');

        // 获取学科信息
        $info = SJ::onlyTrashed()->where('id', $id)->find();
        if ($info)
        {
            $data = $info->restore();
            // 根据更新结果设置返回提示信息
            $data ? $data = ['msg' => '恢复记录成功' , 'val' => 1]
                : $data = ['msg' => '数据处理错误' , 'val' => 0];
        } else {
            $data = ['msg' => '未删除的记录不能恢复' , 'val' => 0];
        }

        // 返回信息
        return json($data);
    }



    // 设置学科是否参加考试
    public function kaoshi()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取学科信息
        $data = SJ::where('id', $id)
            ->update(['kaoshi' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' =>1 ]
            : $dat = ['msg' => '数据处理错误', 'val' =>0 ];

        // 返回信息
        return json($data);
    }


    // 查询学科列名是否重复
    public function srcLieming()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'searchval' => ''
                ,'id'
            ], 'POST');

        $sj = new SJ();
        $data = $sj->onlyLeiming($src);

        return json($data);
    }
}
