<?php

namespace app\teach\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用任务分工数据模型类
use app\teach\model\FenGong as FG;

class FenGong extends AdminBase
{
    /**
     * 任务分工列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '教师任务分工';
        $list['dataurl'] = 'fengong/data';
        $list['status'] = '/teach/fengong/status';
        $list['kaoshi'] = '/teach/fengong/kaoshi';
        $list['nianji'] = \app\facade\Tools::nianJiNameList();

        $fg = new FG();
        $time = strtotime('2021-10-1');
        $qx = $fg->teacherFengong(67, $time);

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取任务分工信息列表
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'update_time'
                ,'order' => 'desc'
                ,'status' => ''
                ,'delete_time' => ''
                ,'searchval' => ''
                ,'subject_id' => ''
                ,'xueqi_id' => ''
                ,'ruxuenian' => ''
            ],'POST');

        if (strlen($src['ruxuenian']) > 0) {
            $bj = new \app\teach\model\Banji;
            $src['banji_id'] = $bj ->where('ruxuenian', $src['ruxuenian'])
                ->where('status', true)
                ->column('id');
        }

        // 按条件查询数据
        $fg = new FG;
        $data = $fg->search($src)
            ->visible([
                'id'
                ,'teacher_id'
                ,'subject_id'
                ,'xueqi_id'
                ,'fgTeacher' => ['id', 'xingming']
                ,'fgSubject' => ['id', 'title']
                ,'fgXueqi' => ['id', 'title']
                ,'bfdate'
            ]);

        $src['all'] = true;
        $cnt = $fg->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建任务分工
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加任务分工'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
        );
        $list['data']['nianji'] = \app\facade\Tools::nianJiNameList();

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
            'teacher_id'
            ,'subject_id'
            ,'banji_id'
            ,'xueqi_id'
            ,'bfdate'
        ], 'post');

        // 验证表单数据
        $validate = new \app\teach\validate\FenGong;

        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }


        $data = array();
        foreach ($list['banji_id'] as $key => $value) {
            foreach ($list['subject_id'] as $k => $val) {
                $data[] = [
                    'teacher_id' => $list['teacher_id']
                    ,'banji_id' => $value
                    ,'subject_id' => $val
                    ,'bfdate' => $list['bfdate']
                    ,'xueqi_id' => $list['xueqi_id']
                ];
            }
        }

        // 保存数据
        $fg = new FG();
        $data = $fg->saveAll($data);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改任务分工信息
    public function edit($id)
    {
        // 获取任务分工信息
        $list['data'] = FG::field('id, banji_id, subject_id, teacher_id, bfdate, xueqi_id')
            ->with([
                'fgBanji' => function ($query) {
                    $query->field('id, ruxuenian, paixu, school_id')
                        ->append(['banjiTitle']);
                }
            ])
            ->find($id);
        $list['data']['nianji'] = \app\facade\Tools::nianJiNameList();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑任务分工'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/teach/fengong/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新任务分工信息
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
        $validate = new \app\teach\validate\fengong;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $data = FG::update($list, ['id'=>$id]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除任务分工
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        // 删除数据
        $data = FG::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置任务分工状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取任务分工信息
        $data = FG::where('id', $id)->update(['status' => $value]);

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

        // 获取任务分工信息
        $info = FG::onlyTrashed()->where('id', $id)->find();
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


    // 设置任务分工是否参加考试
    public function kaoshi()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取任务分工信息
        $data = FG::where('id', $id)
            ->update(['kaoshi' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' =>1 ]
            : $dat = ['msg' => '数据处理错误', 'val' =>0 ];

        // 返回信息
        return json($data);
    }


    // 查询任务分工列名是否重复
    public function srcLieming()
    {
        // 获取参数
        $srcfrom = $this->request
            ->only([
                'searchval' => ''
                ,'id'
            ], 'POST');

        $fg = new FG();
        $data = $fg->onlyLeiming($src);

        return json($data);
    }

    // 创建任务分工
    public function copy()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '复制任务分工'
            ,'butname' => '复制'
            ,'formpost' => 'POST'
            ,'url' => 'savecopy'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('copy');
    }


    // 保存信息
    public function saveCopy()
    {
        // 获取表单数据
        $list = request()->only([
            'xueqi_id'
            ,'yxueqi_id'
            ,'bfdate'
        ], 'post');

        // 验证表单数据
        $validate = new \app\teach\validate\FenGong;

        $result = $validate->scene('copy')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        $fg = new FG();
        $src = [
            'xueqi_id' => $list['yxueqi_id'],
            'all' => true
        ];
        $yuan = $fg->search($src);
        $data = array();
        foreach($yuan as $key=>$value)
        {
            $data[] = [
                'banji_id' => $value->banji_id,
                'subject_id' => $value->subject_id,
                'teacher_id' => $value->teacher_id,
                'bfdate' => $list['bfdate'],
                'xueqi_id' => $list['xueqi_id']
            ];
        }

        // 保存数据
        $fg = new FG();
        $data = $fg->saveAll($data);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
