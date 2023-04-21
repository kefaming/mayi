<?php

namespace app\system\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用类别数据模型类
use app\system\model\Category as CG;

class Category extends AdminBase
{

    // 类别列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '类别列表';
        $list['dataurl'] = '/system/category/data';
        $list['status'] = '/system/category/status';

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
                    ,'field' => 'id'
                    ,'order' => 'asc'
                    ,'p_id' => ''
                    ,'searchval' => ''
                ], 'POST');

        // 按条件查询数据
        $cg = new CG;
        $data = $cg->search($src)
            ->visible([
                'id'
                ,'title'
                ,'paixu'
                ,'glPid' => ['title']
                ,'isupdate'
                ,'flCategory' => ['id','title']
                ,'status'
                ,'update_time'
            ]);
        $src['all'] = true;
        $cnt = $cg->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }



    // 添加类别
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加类别'
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
        $list = request()->only([
            'title'
            ,'p_id'
            ,'paixu'
        ], 'post');

        // 验证表单数据
        $validate = new \app\system\validate\Category;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 保存数据
        $data = CG::create($list);

        // 根据更新结果设置返回提示信息
        $data ? $data=['msg' => '添加成功', 'val' => 1]
            : $data=['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }



    // 编辑类别
    public function edit($id)
    {
        // 获取单位信息
        $list['data'] = CG::field('id, title, p_id, paixu')
            ->find($id);

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑类别'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/system/category/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染
        return $this->view->fetch('create');
    }

    // 更新类别信息
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'p_id'
            ,'paixu'
        ], 'put');
        $list['id'] = $id;

        // 验证是不是被保护分类
        $isupdate = CG::where('id', $id)->value('isupdate');
        if($isupdate == 0)
        {
            $this->error('系统默认分类不允许修改', '/login/err');
        }

        // 验证表单数据
        $validate = new \app\system\validate\Category;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();

        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        $list['id'] = $id;

        $data = CG::cache(true)->update($list);

        // 根据更新结果设置返回提示信息
        $data>=0 ? $data=['msg' => '更新成功', 'val' => 1]
            : $data=['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除类别
    public function delete($id)
    {
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = CG::destroy(function($query) use($id){
            $query->where('isupdate', 1)
                ->where('id', 'in', $id);
        });

        // 根据更新结果设置返回提示信息
        $data ? $data=['msg' => '除系统保留分类以外，其它删除成功', 'val' => 1]
            : $data=['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }

    // 设置类别状态
    public function setStatus()
    {

        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取类别信息
        $data = CG::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data=['msg' => '状态设置成功', 'val' => 1]
            : $data=['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
    
}
