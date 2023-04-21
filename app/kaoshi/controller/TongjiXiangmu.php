<?php
declare (strict_types = 1);

namespace app\kaoshi\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用考试数据模型类
use app\kaoshi\model\TongjiXiangmu as tjxm;

class TongjiXiangmu extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '统计项目设置';
        $list['dataurl'] = '/kaoshi/tjxm/data';
        $list['status'] = '/kaoshi/tjxm/status';
        $list['tongji'] = '/kaoshi/tjxm/tongji';

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染模板
        return $this->view->fetch();
    }


    // 获取考试信息列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => 1
                ,'limit' => '10'
                ,'field' => 'id'
                ,'order' => 'desc'
                ,'searchval' => ''
                ,'category_id' => ''
            ], 'POST');
        // 根据条件查询数据
        $tjxm = new tjxm;
        $data = $tjxm->search($src)
            ->visible([
                'id'
                ,'title'
                ,'tongji'
                ,'paixu'
                ,'status'
                ,'category_id'
                ,'tjxmCategory'
                ,'update_time'
            ]);
        $src['all'] = true;
        $cnt = $tjxm->search($src)->count();
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
            'webtitle' => '新建项目'
            ,'butname' => '创建'
            ,'formpost' => 'POST'
            ,'url' => '/kaoshi/tjxm/save'
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
            'title'
            ,'biaoshi'
            ,'tongji'
            ,'paixu'
        ], 'post');

        // 验证表单数据
        $validate = new \app\kaoshi\validate\TongjiXiangmu;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();

        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 保存数据
        $tjxm = new tjxm();
        $ksdata = $tjxm->create($list);
        $ksdata ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
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
        // 获取考试信息
        $list['data'] = tjxm::where('id', $id)
            ->field('id, title, biaoshi, tongji, paixu')
            ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑项目'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/kaoshi/tjxm/update/' . $id
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
            ,'biaoshi'
            ,'tongji'
            ,'paixu'
        ], 'post');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\kaoshi\validate\TongjiXiangmu;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $tjxm = new tjxm;
        $ksdata = $tjxm::update($list);
        $ksdata ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }

    // 删除考试
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = tjxm::destroy($id);
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置考试状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取考试信息
        $data = tjxm::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置是否参与统计
    public function setTongji()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取考试信息
        $data = tjxm::where('id', $id)->update(['tongji' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
